<template>
    <v-layout row wrap>
        <v-flex d-flex xs12 lg8 xl6>
            <transition name="component-fade" appear>
            <v-card>
                <v-card-text>
                    <v-form ref="jobForm" v-model="valid" lazy-validation>                    
                    <v-layout row wrap>
                        <v-flex row xs12 md6>
                            <v-text-field required :rules="estimateRules" v-model="job.estimate" label="Est" prepend-icon="attach_money"></v-text-field>
                            <v-select
                            autocomplete
                            label="Employee Select"
                            cache-items
                            required
                            :rules="employeeRules"
                            prepend-icon="person_pin"
                            :items="employeeList"
                            v-model="job.employee_id"
                            item-text="name"
                            item-value="id"
                            ></v-select>
                            <v-menu
                            ref="dateMenu"
                            lazy
                            :close-on-content-click="false"
                            v-model="dateMenu"
                            transition="scale-transition"
                            offset-y
                            full-width
                            :nudge-right="40"
                            min-width="290px"
                            :return-value.sync="date"                    
                            >
                                <v-text-field
                                slot="activator"
                                label="Due Date"
                                v-model="job.due_date"
                                prepend-icon="event"
                                readonly
                                :class="{redText: job.vital_date}"                        
                                ></v-text-field>
                                <v-date-picker v-model="job.due_date" no-title scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="dateMenu = false">Cancel</v-btn>
                                <v-btn flat color="primary" @click="$refs.dateMenu.save(date)">OK</v-btn>
                                </v-date-picker>
                            </v-menu>
                            <v-menu
                            ref="completeMenu"
                            lazy
                            :close-on-content-click="false"
                            v-model="completeMenu"
                            transition="scale-transition"
                            offset-y
                            full-width
                            :nudge-right="40"
                            min-width="290px"
                            :return-value.sync="date"
                            v-show="complete"            
                            >
                                <v-text-field
                                slot="activator"
                                label="Completed Date"
                                v-model="job.completed_at"
                                prepend-icon="event"
                                readonly
                                ></v-text-field>
                                <v-date-picker v-model="job.completed_at" no-title scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="completeMenu = false">Cancel</v-btn>
                                <v-btn flat color="primary" @click="$refs.completeMenu.save(completeDate)">OK</v-btn>
                                </v-date-picker>
                            </v-menu>


                        </v-flex>
                        <v-flex row xs12 md6>
                            <v-layout row wrap>   
                            <v-flex xs12>                 
                                <v-text-field multi-line no-resize rows="3" v-model="job.est_note" class="mt-2 est-note-align" label="Estimate Details"></v-text-field>
                            </v-flex>
                            <v-flex xs5>                                     
                                <v-checkbox
                                :label="'Appraisal'"
                                v-model="job.appraisal"
                                ></v-checkbox>
                            </v-flex>                    
                            <v-flex xs5>                                     
                                <v-checkbox
                                :label="'Vital Date'"
                                v-model="job.vital_date"
                                ></v-checkbox>
                            </v-flex>
                            <v-flex xs5>                                     
                                <v-checkbox
                                :label="'Complete'"
                                v-model="complete"
                                ></v-checkbox>
                            </v-flex>
                            </v-layout>             
                        </v-flex>
                        <v-flex xs12>
                                <v-text-field multi-line no-resize v-model="job.note" class="" label="Job Note"></v-text-field>                    
                        </v-flex>
                    </v-layout>
                    </v-form>
                </v-card-text>
                <v-progress-linear v-show="loading" :indeterminate="true" class="mb-0"></v-progress-linear>                      
            </v-card>
            </transition>
        </v-flex>
        <v-flex xs12></v-flex>
        <template v-for="(image, index) in job.job_images" >
            <v-flex d-flex class="xs12 sm12 md6 lg3 xl3" :key="image.id">
                <transition name="component-fade" appear>                    
                <v-card>
                    <v-btn class="close-btn" dark small right absolute outline fab color="grey" @click="removeImage(index)"><v-icon class="fab-fix" dark>delete</v-icon></v-btn>                    
                    <v-card-media :src="image.image" height="200px" @click="showLightBox(image.image)">
                    </v-card-media>
                    <v-text-field v-model="image.note" name="input-1" label=" Note" multi-line rows="5" no-resize></v-text-field>
                </v-card>
                </transition>
            </v-flex>
        </template>
        <v-flex xs12></v-flex>        

            <v-bottom-nav
                fixed
                :value="true"
                class="elevation-1"
            >
                <v-btn v-show="!job.id || job.id == 0" @click="createJob()" class="primary--text">
                <span>Save Job</span>
                <v-icon>save</v-icon>
                </v-btn>
                <v-btn v-show="job.id && job.id !== 0" @click="updateJob()" class="success--text">
                <span>Update Job</span>
                <v-icon>save</v-icon>
                </v-btn>
                <v-btn class="info--text">
                <span>Print</span>
                <v-icon>print</v-icon>
                </v-btn>
                <v-btn @click="captureDialog = true" class="accent--text">
                <span>Capture</span>
                <v-icon>camera_alt</v-icon>
                </v-btn>
                <v-btn v-show="job.id && job.id !== 0" @click="jobDeleteDialog = true" class="error--text">
                <span>Delete Job</span>
                <v-icon>delete</v-icon>
                </v-btn>
                <v-btn v-show="!job.id || job.id == 0" @click="$router.go(-1)" class="error--text">
                <span>Discard Job</span>
                <v-icon>delete</v-icon>
                </v-btn>
            </v-bottom-nav>


        <v-dialog v-model="captureDialog" transition="dialog-transition" fullscreen >
            <v-card>
                <!-- <v-flex d-flex xs12> -->
                <div class="catpure-cont">
                    <video v-show="!img" ref="video" id="video" width="100%" height="100%" autoplay></video>
                    <img class="capture-error" v-show="img" :src="img">                            
                    <!-- <canvas v-show="false" ref="img" id="img" width="1280" height="1024"></canvas> -->
                </div>
                <!-- </v-flex> -->
                <v-flex d-flex xs12>                    
                <v-btn color="primary" @click="saveImage()">Capture</v-btn>
                <v-btn color="error" @click="discardCapture()">discard</v-btn>
                </v-flex>
            </v-card>
        </v-dialog>

        <v-dialog v-model="imageDeleteDialog" max-width="500px">
            <v-card>
                <v-toolbar color="error" dark clipped-left flat>
                    <v-toolbar-title><v-icon>warning</v-icon> Delete Job Image</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    Are you sure you want to delete this image? <br>
                    This image was previously saved to this job. <br>
                    This action is not reversable
                </v-card-text>
                <v-card-actions>
                    <v-btn color="error"  @click.stop="deleteImage()">
                        <v-icon>delete</v-icon>
                        Delete
                    </v-btn>                    
                    <v-btn color="primary" right absolute @click.stop="imageDeleteDialog=false">
                        <v-icon>cancel</v-icon>
                        Cancel
                        </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="jobDeleteDialog" max-width="500px">
            <v-card>
                <v-toolbar color="error" dark clipped-left flat>
                    <v-toolbar-title><v-icon>warning</v-icon>Delete Job</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    Are you sure you want to delete this Job? <br>
                    This will also delete all images attached to this job <br>
                    This action is not reversable
                </v-card-text>
                <v-card-actions>
                    <v-btn color="error"  @click.stop="deleteJob()">
                        <v-icon>delete</v-icon>
                        Delete
                    </v-btn>                    
                    <v-btn color="primary" right absolute @click.stop="jobDeleteDialog=false">
                        <v-icon>cancel</v-icon>
                        Cancel
                        </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <transition name="component-lightbox" appear>                            
            <div v-if="lightBoxDialog" class="lightBoxWrapper" @click="lightBoxDialog = false">
                <img :src='lightBoxImage' alt="" class="lightBoxImage">
            </div>
        </transition>
        <span v-if="job.id" class="cb-print">
            <div class="cb-print-element cb-print-note">
                {{job.note}}
            </div>
            <div class="cb-print-element cb-print-job-num">
                <v-icon class="cb-print-element cb-print-work-icon">work</v-icon>{{ job.id }}
            </div>
            <div class="cb-print-element cb-print-estimate">
                <div class="cb-print-est-amt"> Est: ${{ job.estimate}} </div><br>
                <div class="cb-print-est-note"> {{ job.est_note }} </div>
            </div>
            <div :class="{cbPrintRed: job.vital_date}"  class="cb-print-element cb-print-due">
                <v-icon class="cb-print-element cb-print-alarm-icon">alarm</v-icon>{{ job.due_date }}
            </div>
            <div class="cb-print-element cb-print-images">
                <template v-for="(image) in job.job_images">
                    <div class="cb-print-element cb-print-image-cont" :key="image.id">
                      <img :src='image.image' alt="" class="cb-print-image cb-print-element">
                      <div class="cb-print-element cb-print-image-note">{{image.note}}</div>                   
                    </div>
                </template>
            </div>
            <img class="cb-print-logo cb-print-element" src="img/logo.png" alt="">
            <div class="cb-print-element cb-print-cus-images">
                <template v-for="(image) in job.job_images">
                    <div class="cb-print-element cb-print-cus-img-cont" :key="image.id">
                      <img :src='image.image' alt="" class="cb-print-cust-img cb-print-element">
                    </div>
                </template>
          
            </div>
            <div class="cb-print-element cb-print-cus-job-info">
                Date: {{ today }} {{ now }} <br>
                Employee: {{ employeeName }} <br>
                Phone: 403-320-0846 <br>
                E-mail: goldmail@thegoldworks.com
            </div>
            <div class="cb-print-element cb-print-cus-estimate">
                <div class="cb-print-est-amt"> Estimate: ${{ job.estimate}} </div><br>
                <div class="cb-print-est-note"> {{ job.est_note }} </div>
            </div>
            <div class="cb-print-element cb-print-cus-warning">
                The Goldworks is not responsible for any items held for over 90 days
            </div>      
        </span>    
    </v-layout>

</template>

<script>

    export default {
        data: () => ({
            valid: true,
            loading: false,
            date: null,
            completeDate: null,
            dateMenu: false,
            completeMenu: false,
            complete: false,
            captureDialog: false,
            imageDeleteDialog: false,
            jobDeleteDialog: false,
            lightBoxDialog: false,
            lightBoxImage: null,
            deleteImageId: null,
            deleteImageIndex: null,
            employee: null,
            employeeList: [],
            img: {},
            video: {},
            job: {
                id: null,
                customer_id: null,
                employee_id: null,
                estimate: null,
                est_note: null,
                appraisal: false,
                note: null,
                due_date: null,
                completed_at: null,
                vital_date: false,
                job_images: []
            },
            estimateRules: [
                v => !!v || 'Estimate is required',
                v => {
                    var pattern = new RegExp(/^\d*(,\d+)*[\.]?\d*?$/);
                    return pattern.test(v) || "Must be a valid number.";
                }
            ],
            employeeRules: [
                v => !!v || 'Select employee'
            ]
        }),
        methods: {
            saveImage() {
                this.img = this.$refs.img;
                var context = this.img.getContext("2d").drawImage(this.video, 0, 0, 1280, 1024);
                this.job.job_images.push({
                    image: this.img.toDataURL("image/png"),
                    note: null,
                    id: null
                });
                this.img = null;
                this.captureDialog = false;
            },
            discardCapture() {
                this.captureDialog = false;
            },
            removeImage(index) {
                if (this.job.job_images[index].id !== null) {
                    this.deleteImageId = this.job.job_images[index].id;
                    this.deleteImageIndex = index;
                    this.imageDeleteDialog = true;
                } else {
                    this.job.job_images.splice(index, 1);                
                }
            },
            showLightBox(image) {
                this.lightBoxImage = image;
                this.lightBoxDialog = true;
            },
            getEmployees() {
                axios.get('/employees/active')
                    .then((response) => {
                        this.employeeList = response.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            createJob() {
                this.$refs.jobForm.validate();
                if (!this.customer_id) {
                    this.store.setAlert(true, "error", "Please select a customer.");
                    return;
                }
                if (!this.valid) {
                    this.store.setAlert(true, "error", "Please fix required fields");
                    return;
                }
                this.loading = true;                
                axios.post('jobs/create', this.job)
                    .then((response) => {
                        this.job.id = response.data.id;
                        var i = 0;
                        response.data.image_ids.forEach(id => {
                            this.job.job_images[i].id = id;
                            i++;
                        });
                        this.store.setAlert(true, "success", "Job Create with ID: " + this.job.id);
                        this.$router.replace("/job/" + this.job.id);
                        this.loading = false;                                                                                                                                                        
                    })
                    .catch((error) => {
                        console.log(error);
                        this.loading = false;
                        this.store.setAlert(true, "error", error.message);                                                                    
                    });
            },
            updateJob() {
                this.$refs.jobForm.validate();
                if (!this.customer_id) {
                    this.store.setAlert(true, "error", "Please select a customer.");
                    return;
                }
                if (!this.valid) {
                    this.store.setAlert(true, "error", "Please fix required fields");
                    return;
                }
                this.loading = true;                
                axios.post('jobs/update', this.job)
                    .then((response) => {
                        this.job.id = response.data.id;
                        var i = 0;
                        response.data.image_ids.forEach(id => {
                            this.job.job_images[i].id = id;
                            i++;
                        });
                        this.store.setAlert(true, "success", "Job Upated Successfully");
                        this.loading = false;                                            
                    })
                    .catch((error) => {
                        console.log(error);
                        this.loading = false;
                        this.store.setAlert(true, "error", error.message);                                            
                    });
            },
            getJob(id) {
                this.loading = true;
                axios.post('jobs/show', {id: id})
                    .then((response) => {
                        this.job.id = response.data.id;
                        this.job.customer_id = response.data.customer_id;
                        this.job.employee_id = response.data.employee_id;
                        this.job.estimate = response.data.estimate.toLocaleString();
                        this.job.est_note = response.data.est_note;
                        this.job.note = response.data.note;                        
                        this.job.appraisal = response.data.appraisal;
                        this.job.due_date = response.data.due_date;
                        this.job.completed_at = response.data.completed_at;
                        this.job.vital_date = response.data.vital_date;                        
                        this.job.job_images = response.data.job_images;

                        this.$emit('customerId', this.job.customer_id);

                        if (this.job.completed_at !== null) this.complete = true;

                        this.loading = false;
                    })
                    .catch((error) => {
                        this.store.setAlert(true, "error", "Job ID " + id + " not found.");
                        this.loading = false;
                        console.log(error);
                    });
            },
            deleteImage() {
                axios.post('job_images/delete', {id: this.deleteImageId})
                    .then((response) => {
                        this.job.job_images.splice(this.deleteImageIndex, 1);
                        this.deleteImageId = null;
                        this.deleteImageIndex = null;
                        this.imageDeleteDialog = false;                                                                                       
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            },
            deleteJob() {
                axios.post('jobs/delete', {id: this.job.id})
                    .then((response) => {
                        // this.$router.push("/");
                        this.jobDeleteDialog = false;
                        this.store.setAlert(true, "warning", "Job ID " + this.job.id + " deleted.");                        
                        this.$router.go(-1);
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            }
        },
        mounted() {
            this.getEmployees();
            this.video = this.$refs.video;

            if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: true }).catch(stream => {
                    try {
                        this.video.srcObject = stream;
                    } catch (error) {
                        // this.video.src = URL.createObjectURL(stream);
                        console.log('Could not create video stream');
                        this.img = "img/webcamError.png";
                    }                   
                    this.video.play();
                });
            }
        },
        props: {
            customer_id: Number,
            job_id: Number
        },
        watch: {
            customer_id (val) {
                if (!isNaN(this.customer_id) && this.customer_id !== null) {
                    this.job.customer_id = val;
                }
            },
            job_id (val) {
                if (!isNaN(this.job_id) && this.job_id !== null) {
                    this.getJob(this.job_id);
                }
            },
            complete (val) {
                if (val && this.job.completed_at == null) this.job.completed_at = this.today;
                if (!val) this.job.completed_at = null;
                
            }                
        },
        computed: {
            store() {
                return this.$root.$data.store;
            },
            today() {
                // Date = new Date();
                var today = new Date();
                var yyyy = today.getFullYear();
                var mm = (1+today.getMonth());
                var dd = today.getDate();

                if (mm < 10 ) {
                    mm = "0" + mm;
                }
                if (dd < 10 ) {
                    dd = "0" + dd;
                }

                return yyyy + "-" + mm + "-" + dd;
            },
            now() {
                var now = new Date()
                return now.getHours() + ":" + now.getMinutes();
            },
            employeeName() {
                if (this.employeeList[this.job.employee_id - 1]) {
                    return this.employeeList[this.job.employee_id - 1].name;
                } else {
                    return null;
                }
            }
        }
    }
</script>
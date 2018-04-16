<template>
    <v-layout row wrap>
        <v-flex d-flex xs12 md6>
            <transition name="component-fade" appear>
            <v-card>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex row xs12 md6>
                            <v-text-field v-model="job.estimate" label="Est" prepend-icon="attach_money"></v-text-field>
                            <v-select
                            autocomplete
                            label="Employee Select"
                            cache-items
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
                </v-card-text>
            </v-card>
            </transition>
        </v-flex>
        <v-flex xs12></v-flex>
        <template v-for="(image, index) in job.job_images" >
            <v-flex :key="image.image" md4>
                <transition name="component-fade" appear>                    
                <v-card class="" width="400px">
                    <v-btn class="close-btn" dark small right absolute outline fab color="grey" @click="removeImage(index)"><v-icon class="fab-fix" dark>delete</v-icon></v-btn>                    
                    <v-card-media contain :src="image.image" height="200px" @click="showLightBox(image.image)">
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
                <v-btn v-show="!job.id" @click="createJob()" class="primary--text">
                <span>Save Job</span>
                <v-icon>save</v-icon>
                </v-btn>
                <v-btn v-show="job.id" @click="updateJob()" class="success--text">
                <span>Update Job</span>
                <v-icon>save</v-icon>
                </v-btn>
                <v-btn class="info--text">
                <span>Print</span>
                <v-icon>print</v-icon>
                </v-btn>
                <v-btn @click="caputureDialog = true" class="accent--text">
                <span>Capture</span>
                <v-icon>camera_alt</v-icon>
                </v-btn>
                <v-btn class="error--text">
                <span>Delete Job</span>
                <v-icon>delete</v-icon>
                </v-btn>
            </v-bottom-nav>


        <v-dialog  v-model="caputureDialog" transition="dialog-transition">
            <v-card>
                <v-toolbar style="flex: 0 0 auto;" dark class="primary">
                    <v-btn icon @click.native="caputureDialog = false" dark>
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>New Job Bag Image</v-toolbar-title>
                </v-toolbar>
                <div >
                    <webcam v-show="!img" ref="webcam" :height="600" :width="800" screenshotFormat="image/png"></webcam>
                    <img v-show="img" v-bind:src="img" alt="">
                </div>
                <v-btn color="primary" @click="photo()">Capture</v-btn>
                <v-btn color="primary" @click="saveImage()">Save</v-btn>
                <v-btn color="error" @click="discardCapture()">discard</v-btn>
            </v-card>
        </v-dialog>

        <v-dialog v-model="deleteDialog" max-width="500px">
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
                    <v-btn color="primary" right absolute @click.stop="deleteDialog=false">
                        <v-icon>cancel</v-icon>
                        Cancel
                        </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog max-width="50%" v-model="lightboxDialog" transition="dialog-transition">
            <v-card>
                <v-layout row justify-center>
                <v-flex xs2>
                    <img  v-bind:src="lightBoxImage" alt="">
                </v-flex>
                </v-layout>
            </v-card>
        </v-dialog>
        
    </v-layout>

</template>

<script>
    import Webcam from 'vue-web-cam/src/webcam'

    export default {
        data: () => ({
            date: null,
            completeDate: null,
            dateMenu: false,
            completeMenu: false,
            complete: false,
            caputureDialog: false,
            deleteDialog: false,
            lightboxDialog: false,
            lightBoxImage: null,
            deleteImageId: null,
            deleteImageIndex: null,
            employee: null,
            employeeList: [],
            img: null,
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
            }
        }),
        methods: {
            photo() {
                this.img = this.$refs.webcam.capture();
            },
            saveImage() {
                this.job.job_images.push({
                    image: this.img,
                    note: null,
                    id: null
                });
                this.img = null;
                this.caputureDialog = false;
            },
            discardCapture() {
                this.img = null;
            },
            removeImage(index) {
                if (this.job.job_images[index].id !== null) {
                    this.deleteImageId = this.job.job_images[index].id;
                    this.deleteImageIndex = index;
                    this.deleteDialog = true;
                } else {
                    this.job.job_images.splice(index, 1);                
                }
            },
            showLightBox(image) {
                this.lightBoxImage = image;
                this.lightboxDialog = true;
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
                axios.post('jobs/create', this.job)
                    .then((response) => {
                        this.job.id = response.data.id;
                        var i = 0;
                        response.data.image_ids.forEach(id => {
                            this.job.job_images[i].id = id;
                            i++;
                        });
                        this.store.setAlert(true, "success", "Job Create with ID: " + this.job.id);                                            
                    })
                    .catch((error) => {
                        console.log(error);
                        this.store.setAlert(true, "error", error.message);                                                                    
                    });
            },
            updateJob() {
                axios.post('jobs/update', this.job)
                    .then((response) => {
                        this.job.id = response.data.id;
                        var i = 0;
                        response.data.image_ids.forEach(id => {
                            this.job.job_images[i].id = id;
                            i++;
                        });
                        this.store.setAlert(true, "success", "Job Upated Successfully");                    
                    })
                    .catch((error) => {
                        console.log(error);
                        this.store.setAlert(true, "error", error.message);                                            
                    });
            },
            getJob(id) {
                axios.post('jobs/show', {id: id})
                    .then((response) => {
                        this.job.id = response.data.id;
                        this.job.customer_id = response.data.customer_id;
                        this.job.employee_id = response.data.employee_id;
                        this.job.estimate = response.data.estimate;
                        this.job.est_note = response.data.est_note;
                        this.job.note = response.data.note;                        
                        this.job.appraisal = response.data.appraisal;
                        this.job.due_date = response.data.due_date;
                        this.job.completed_at = response.data.completed_at;
                        this.job.vital_date = response.data.vital_date;                        
                        this.job.job_images = response.data.job_images;

                        this.$emit('customerId', this.job.customer_id);

                        if (this.job.completed_at !== null) this.complete = true;
                    })
                    .catch((error) => {
                        this.store.setAlert(true, "error", "Job ID " + id + " not found.");
                        console.log(error);
                    });
            },
            deleteImage() {
                axios.post('job_images/delete', {id: this.deleteImageId})
                    .then((response) => {
                        this.job.job_images.splice(this.deleteImageIndex, 1);
                        this.deleteImageId = null;
                        this.deleteImageIndex = null;
                        this.deleteDialog = false;                                                                                       
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            }
        },
        components: {
            Webcam
        },
        mounted() {
            this.getEmployees();
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
            }
        }
    }
</script>
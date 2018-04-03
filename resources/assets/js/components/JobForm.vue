<template>
    <div>
        <v-flex xs12 sm12 md4>
            <transition name="component-fade" appear>
            <v-card class="ma-3">
                <v-card-text>
                <div>
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
                    <v-layout>
                <v-flex xs12 sm3>                    
                    <v-text-field v-model="job.estimate" label="Est" prepend-icon="attach_money"></v-text-field>
                </v-flex>
                <v-flex xs12 sm9>                    
                    <v-text-field v-model="job.est_note" class="ml-1" label="Estimate Note"></v-text-field>
                </v-flex>
                </v-layout>
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
                    ></v-text-field>
                    <v-date-picker v-model="job.due_date" no-title scrollable>
                    <v-spacer></v-spacer>
                    <v-btn flat color="primary" @click="dateMenu = false">Cancel</v-btn>
                    <v-btn flat color="primary" @click="$refs.dateMenu.save(date)">OK</v-btn>
                    </v-date-picker>
                </v-menu>
                </div>
                <div class="cdb-bottom-right">
                    <v-btn fab small bottom right dark color="primary" @click="dialog = true"><v-icon class="fab-fix" dark>camera_alt</v-icon></v-btn>                    
                </div> 
                </v-card-text>
            </v-card>
            </transition>
        </v-flex>
            <v-layout>
            <template v-for="(image, index) in job.job_images" >
                <v-flex :key="image.image" md4>
                    <transition name="component-fade" appear>                    
                    <v-card class="ma-3">
                        <v-card-media :src="image.image" height="200px">
                        </v-card-media>
                        <v-text-field v-show="image.note" v-model="image.note" name="input-1" label=" Note" multi-line rows="5"></v-text-field>
                        <div class="cdb-bottom-right">
                            <v-btn v-show="!image.note" fab dark small color="primary" @click="image.note = ' '"><v-icon class="fab-fix" dark>edit</v-icon></v-btn>
                            <v-btn v-show="image.note" fab dark small color="primary" @click="image.note = null"><v-icon class="fab-fix" dark>close</v-icon></v-btn>                            
                            <v-btn fab dark small color="error" @click="removeImage(index)" ><v-icon class="fab-fix" dark>delete</v-icon></v-btn>
                        </div>
                    </v-card>
                    </transition>
                </v-flex>
            </template>
            </v-layout>
            <transition name="component-fade" appear>                                
            <div>
                <v-btn v-show="!job.id" color="primary" @click="createJob()" ><v-icon>save</v-icon>Save Job</v-btn>
                <v-btn v-show="job.id" color="success" @click="updateJob()" ><v-icon>save</v-icon>Update Job</v-btn>                
                <v-btn color="white"><v-icon>print</v-icon>Print</v-btn>
            </div>
            </transition>


        <v-dialog  v-model="dialog" transition="dialog-transition">
            <v-card>
                <v-toolbar style="flex: 0 0 auto;" dark class="primary">
                    <v-btn icon @click.native="dialog = false" dark>
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
    </div>

</template>

<script>
    import Webcam from 'vue-web-cam/src/webcam'

    export default {
        data: () => ({
            date: null,
            dateMenu: false,
            dialog: false,
            deleteDialog: false,
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
                due_date: null,
                completed_at: null,
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
                this.dialog = false;
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
            getEmployees() {
                axios.get('/employees/index')
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
                    })
                    .catch((error) => {
                        console.log(error);
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
                    })
                    .catch((error) => {
                        console.log(error);
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
                        this.job.appraisal = response.data.appraisal;
                        this.job.due_date = response.data.due_date;
                        this.job.completed_at = response.data.completed_at;
                        this.job.job_images = response.data.job_images;

                        this.$emit('customerId', this.job.customer_id);
                    })
                    .catch((error) => {
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
            }                
        }
    }
</script>
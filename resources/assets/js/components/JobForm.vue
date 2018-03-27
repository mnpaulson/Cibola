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
                    v-model="employee"
                    item-text="name"
                    item-value="id"
                    ></v-select>
                <v-text-field label="Estimate" prepend-icon="attach_money"></v-text-field>
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
                    v-model="date"
                    prepend-icon="event"
                    readonly
                    ></v-text-field>
                    <v-date-picker v-model="date" no-title scrollable>
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
            <template v-for="(image, index) in jobImages" >
                <v-flex :key="image.image" md4>
                    <transition name="component-fade" appear>                    
                    <v-card class="ma-3">
                        <v-card-media :src="image.image" height="200px">
                        </v-card-media>
                        <v-text-field v-show="image.note" name="input-1" label=" Note" multi-line rows="5"></v-text-field>
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
                <v-btn color="primary"><v-icon>save</v-icon>Save Job</v-btn>
                <v-btn color="white"><v-icon>print</v-icon>Print</v-btn>
            </div>
            </transition>


        <v-dialog width="50%" v-model="dialog" transition="dialog-transition">
            <v-card>
                <v-toolbar style="flex: 0 0 auto;" dark class="primary">
                    <v-btn icon @click.native="dialog = false" dark>
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>New Job Bag Image</v-toolbar-title>
                </v-toolbar>
                <div >
                    <webcam v-show="!img" ref="webcam" :height="600" :width="800"></webcam>
                    <img v-show="img" v-bind:src="img" alt="">
                </div>
                <v-btn color="primary" @click="photo()">Capture</v-btn>
                <v-btn color="primary" @click="saveImage()">Save</v-btn>
                <v-btn color="error" @click="discardCapture()">discard</v-btn>
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
            employee: null,
            employeeList: [],
            img: null,
            jobImages: []
        }),
        methods: {
            photo() {
                this.img = this.$refs.webcam.capture();
            },
            saveImage() {
                this.jobImages.push({
                    image: this.img,
                    note: null
                });
                this.img = null;
                this.dialog = false;
            },
            discardCapture() {
                this.img = null;
            },
            removeImage(index) {
                this.jobImages.splice(index, 1);
            },
            getEmployees() {
                axios.get('/employees/index')
                    .then((response) => {
                        this.employeeList = response.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        components: {
            Webcam
        },
        mounted() {
            this.getEmployees();
        }
    }
</script>
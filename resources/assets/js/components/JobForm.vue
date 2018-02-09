<template>
    <div>
        <v-flex xs12 sm12 md4>
            <v-card>
                <v-card-text>
                    <v-select
                    autocomplete
                    label="Employee Select"
                    cache-items
                    prepend-icon="person_pin"
                    ></v-select>
                <v-text-field label="Estimate" prepend-icon="attach_money"></v-text-field>
                <v-menu lazy :close-on-content-click="false" v-model="dateMenu" transition="scale-transition" offset-y full-width :nudge-right="40"
                    max-width="290px" min-width="290px">
                    <v-text-field slot="activator" label="Due Date" v-model="date" prepend-icon="event" readonly></v-text-field>
                    <v-date-picker v-model="date" no-title scrollable actions autosave>
                        <template slot-scope="{ save, cancel }">
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="cancel">Cancel</v-btn>
                                <v-btn flat color="primary" @click="save">OK</v-btn>
                            </v-card-actions>
                        </template>
                    </v-date-picker>
                </v-menu>
                </v-card-text>
                <div class="cdb-bottom-right">
                    <v-btn fab dark small color="primary" @click="dialog = true"><v-icon dark>camera_alt</v-icon></v-btn>                    
                </div>             
            </v-card>
        </v-flex>
            <v-layout>
            <template v-for="(image, index) in jobImages" >
                <v-flex :key="image.image" md4>
                    <v-card>
                        <v-card-media :src="image.image" height="200px">
                        </v-card-media>
                        <v-text-field v-show="image.note" name="input-1" label=" Note" multi-line rows="5"></v-text-field>
                        <div class="cdb-bottom-right">
                            <v-btn v-show="!image.note" fab dark small color="primary" @click="image.note = ' '"><v-icon dark>edit</v-icon></v-btn>
                            <v-btn v-show="image.note" fab dark small color="primary" @click="image.note = null"><v-icon dark>close</v-icon></v-btn>                            
                            <v-btn fab dark small color="error" @click="removeImage(index)" ><v-icon dark>delete</v-icon></v-btn>
                        </div>
                    </v-card>
                </v-flex>
            </template>
            </v-layout>
            <v-btn color="primary"><v-icon>save</v-icon>Save Job</v-btn>
            <v-btn color="white"><v-icon>print</v-icon>Print</v-btn>


        <v-dialog v-model="dialog" fullscreen transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar style="flex: 0 0 auto;" dark class="primary">
                    <v-btn icon @click.native="dialog = false" dark>
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>New Job Bag Image</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark flat @click.native="dialog = false">Save</v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <div :style="{transform: 'translate(20%, 0%)'}">
                    <webcam v-show="!img" ref="webcam" :height="600" :width="800"></webcam>
                </div>
                <div>
                    <vue-cropper v-show="img" ref='cropper' :guides=true :view-mode=2 drag-mode="crop" :auto-crop-area=0.5 :min-container-width=400
                        :min-container-height=300 :background=false :aspectRatio=4/3 :modal=false :src="this.img" alt="Source Image">
                    </vue-cropper>
                </div>
                <v-btn color="primary" @click="photo()">Capture</v-btn>
                <v-btn color="primary" @click="cropImage()">Crop</v-btn>
                <v-btn color="error" @click="discardCapture()">discard</v-btn>
                
                <v-card-text>
                    <v-divider></v-divider>
                </v-card-text>

                <div style="flex: 1 1 auto;"></div>
            </v-card>
        </v-dialog>
    </div>
</template>

<script src="/path/to/cropper.js"></script>
<script>
    import Webcam from 'vue-web-cam/src/webcam'
    import VueCropper from 'vue-cropperjs';

    export default {
        data: () => ({
            date: null,
            dateMenu: false,
            dialog: false,
            img: null,
            cropImg: null,
            jobImages: []
        }),
        methods: {
            photo() {
                this.img = this.$refs.webcam.capture();
                this.cropper();
            },
            cropper() {
                this.$refs.cropper.replace(this.img);
            },
            cropImage() {
                this.cropImg = this.$refs.cropper.getCroppedCanvas().toDataURL();
                this.jobImages.push({
                    image: this.cropImg,
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
            }
        },
        components: {
            Webcam
        }
    }
</script>
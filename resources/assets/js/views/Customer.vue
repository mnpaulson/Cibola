<template>
    <div>
        <customer-form :id.sync="id" v-on:newCustomer="update()"></customer-form>
        <v-fade-transition>                  
        <!-- <v-flex d-flex class=""> -->
            <div v-show="id" >
                <v-layout>
                <v-flex d-flex class="xs6 sm2">
                    <v-btn color="primary" :href="'#/job/0/' + id">
                        <v-icon>add</v-icon>
                        New Job
                    </v-btn>
                </v-flex>
                <v-flex d-flex class="xs6 sm2">
                    <v-btn color="error" @click="deleteDialog = true" :style="{'font-size': '0.75em'}">
                        <v-icon>delete</v-icon>
                        Delete Customer
                    </v-btn>
                </v-flex>
                </v-layout>
            </div>
        <!-- </v-flex> -->
        </v-fade-transition>   
        <transition name="component-fade" appear>                                
        <v-flex d-flex xs12 md12 lg8 xl6  class="mt-2">
            <v-card v-show="!id" class="">
                <!-- <v-toolbar color="indigo" dark clipped-left flat>
                    <v-toolbar-title>Customers</v-toolbar-title>
                </v-toolbar> -->
                <v-card-title>
                    <v-card-title primary-title>
                        <h3 class="headline mb-0">Customers</h3>
                    </v-card-title>
                    <v-spacer></v-spacer>
                    <v-text-field
                        append-icon="search"
                        label="Search"
                        single-line
                        hide-details
                        v-model="searchCus"
                    ></v-text-field>
                </v-card-title>
                <template>
                    <v-data-table v-bind:headers="customerHeaders" :items="customers" v-bind:pagination.sync="paginationCus" class="elevation-1" :search="searchCus" :loading="loadingCustomers">
                        <template slot="items" slot-scope="props">
                            <tr @click="setId(props.item.id)">
                                <td class="text-xs-left">{{ props.item.fname }}</td>
                                <td class="text-xs-left">{{ props.item.lname }}</td>
                                <td class="text-xs-left">{{ props.item.created_at }}</td>
                                <td class="text-xs-left">{{ props.item.updated_at }}</td>
                            </tr>
                        </template>
                    </v-data-table>
                </template>
            </v-card>
            <v-card v-show="id" class="">
                <v-card-title>
                    <v-card-title primary-title>
                        <h3 class="headline mb-0">Jobs</h3>
                    </v-card-title>
                    <v-spacer></v-spacer>
                    <v-text-field
                        append-icon="search"
                        label="Search"
                        single-line
                        hide-details
                        v-model="searchJob"
                    ></v-text-field>
                </v-card-title>
                    <v-data-table v-bind:headers="jobHeaders" :items="jobs" v-bind:pagination.sync="paginationJob" class="elevation-1" :search="searchJob" :loading="loadingJobs">
                        <template slot="items" slot-scope="props">
                            <tr @click="goToJob(props.item.id)">
                                <td class="text-xs-center">{{ props.item.id }}</td>
                                <td class="text-xs-left hidden-sm-and-down">${{ props.item.estimate.toLocaleString() }}</td>
                                <td class="text-xs-left hidden-sm-and-down">{{ props.item.created_at }}</td>
                                <td class="text-xs-left" v-bind:class="{'vital-date': props.item.vital_date && !props.item.completed_at}">{{ props.item.due_date }}</td>
                                <td class="text-xs-left">{{ props.item.completed_at }}</td>                                
                            </tr>
                        </template>
                    </v-data-table>
            </v-card>
        </v-flex>
        </transition>               
        <v-dialog v-model="deleteDialog" max-width="500px">
            <v-card xs12 md6>
                <v-toolbar color="error" dark clipped-left flat>
                    <v-toolbar-title><v-icon>warning</v-icon> Delete Customer</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    Are you sure you want to delete this customer? <br>
                    This will also delete all of this customer's jobs.
                </v-card-text>
                <v-card-actions>
                    <v-btn color="error"  @click.stop="deleteCustomer()">
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
    export default {
        data: () => ({
            id: null,
            searchCus: null,
            searchJob: null,            
            deleteDialog: false,
            loadingCustomers: false,
            loadingJobs: false,
            customers: [],
            jobs: [],
            jobHeaders: [{
                    text: 'ID',
                    value: 'id'
                },
                {
                    text: 'Estimate',
                    value: 'estimate',
                    class: 'hidden-sm-and-down'
                },
                {
                    text: 'Created',
                    value: 'created_at',
                    class: 'hidden-sm-and-down'
                },
                {
                    text: 'Due Date',
                    value: 'due_date'
                },
                {
                    text: 'Completed',
                    value: 'completed_at'
                }
            ],
            customerHeaders: [{
                    text: 'First Name',
                    value: 'fname'
                },
                {
                    text: 'Last Name',
                    value: 'lname'
                },
                {
                    text: 'Created',
                    value: 'created_at'
                },
                {
                    text: 'Updated',
                    value: 'updated_at'
                },
            ],
            paginationCus: {
              sortBy: 'created_at',
              descending: true,
              rowsPerPage: 10
            },
            paginationJob: {
              sortBy: 'due_date',
              descending: true,
              rowsPerPage: 10
            },
        }),
        watch: {
            id(val) {
                if (this.id == null) {
                    this.$router.push("/customer");
                    this.jobs = [];
                    if (this.customers.length == 0) this.getCustomers();                                    
                } else {
                    this.getCustomerJobs();
                }
            },
            // Handle changing between customer view and no customer selected
            '$route' (to, from) {
                if (!to.params.id) this.id = null;
                else this.id = Number(to.params.id);
            }
        },

        methods: {

            getCustomers() {
                this.loadingCustomers = true;
                axios.get('/customers/index')
                    .then((response) => {
                        this.customers = response.data;
                        this.loadingCustomers = false;
                    })
                    .catch((error) => {
                        console.log(error);
                        this.loadingCustomers = false;                        
                    });
            },
            getCustomerJobs() {
                this.loadingJobs = true;
                axios.post('/jobs/customerJobs', {id: this.id})
                    .then((response) => {
                        this.jobs = response.data;
                        this.loadingJobs = false;
                    })
                    .catch((error) => {
                        console.log(error);
                        this.loadingJobs = false;                        
                    });     
            },
            setId(val) {
                this.id = Number(val);
                this.$router.push("/customer/" + val);
            },

            deleteCustomer() {
                this.deleteDialog = false;
                axios.post('/customers/delete', {id: this.id})
                    .then((response) => {
                        this.id = null;
                        this.update();
                    })
                    .catch((error) => {
                        console.log(error);
                    }); 
            },

            update() {
                this.getCustomers();
            },
            goToJob(id) {
                this.$router.push('/job/' + id);
            }
        },
        mounted() {
            if (this.$route.params.id) {
                this.id = Number(this.$route.params.id);
                // this.getCustomerJobs();
            } else {
                this.getCustomers();
            }
        }
    }
</script>
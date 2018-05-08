<template>
    <div>
    <v-layout row wrap v-show="job_id !== null || customer_id !== null">
        <customer-form :id.sync="customer_id"></customer-form>
    </v-layout>  
    <job-form v-show="job_id !== null || customer_id !== null" :job_id.sync="job_id" :customer_id.sync="customer_id" v-on:customerId="setCustomerId"></job-form>
    <v-layout v-if="job_id == null && customer_id == null">
        <job-lookup></job-lookup>    
    </v-layout>
    <transition name="component-fade" appear>            
    <v-layout v-if="job_id == null && customer_id == null">    
        <v-flex row wrap xs12>
            <v-card>
                <v-card-title>
                    <v-card-title primary-title>
                        <h3 class="headline mb-0">Jobs</h3>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" :href="'#/job/0/0'">
                            <v-icon>add</v-icon>
                            New Job
                        </v-btn>
                    </v-card-title>
                    <v-spacer></v-spacer>
                    <!-- <v-text-field
                        append-icon="search"
                        label="Search"
                        single-line
                        hide-details
                        v-model="searchJob"
                    ></v-text-field> -->
                </v-card-title>
                    <v-data-table v-bind:headers="jobHeaders" :items="jobs" v-bind:pagination.sync="paginationJob" class="elevation-1" :search="searchJob" :total-items="totalJobs" :loading="loading">
                        <template slot="items" slot-scope="props">
                            <tr @click="goToJob(props.item.id)">
                                <td class="text-xs-left">{{ props.item.id }}</td>
                                <td class="text-xs-right">${{ props.item.estimate.toLocaleString() }}</td>
                                <td class="text-xs-left">{{ props.item.customer.fname }} {{ props.item.customer.lname }}</td>
                                <td class="text-xs-left" v-if="props.item.employee">{{ props.item.employee.name }}</td>
                                <td class="text-xs-left" v-else>Unassigned</td>                                                                                                                                                
                                <td class="text-xs-left">{{ props.item.created_at }}</td>
                                <td class="text-xs-left" v-bind:class="{'vital-date': props.item.vital_date && !props.item.completed_at}">{{ props.item.due_date }}</td>
                                <td class="text-xs-left">{{ props.item.completed_at }}</td>                                
                            </tr>
                        </template>
                    </v-data-table>
            </v-card>
        </v-flex>
    </v-layout>
    </transition>
    </div>  
</template>

<script>
    export default {
        data: () => ({
            customer_id: null,
            job_id: null,
            searchJob: null,            
            jobs: [],
            totalJobs: 0,
            loading: true,
            jobHeaders: [{
                    text: 'ID',
                    value: 'id'
                },
                {
                    text: 'Estimate',
                    value: 'estimate',
                    align: 'right'
                },
                {
                    text: 'Name',
                    value: 'fname'
                },
                {
                    text: 'Employee',
                    value: 'employee'
                },
                {
                    text: 'Created',
                    value: 'created_at'
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
            paginationJob: {
                rowsPerPage: 25,
                sortBy: 'id',
                descending: true
            }
        }),
        watch: {
            // Handle changing between customer view and no customer selected
            '$route' (to, from) {
                // if (!to.params.id) this.job_id = null;
                // else this.job_id = Number(to.params.id);
                if (isNaN(Number(to.params.id))) {
                    this.job_id = null
                    this.customer_id = null;
                }
                else if (Number(to.params.id) !== 0) this.job_id = Number(to.params.id);
                else this.customer_id = Number(to.params.cus);
            },
            paginationJob: {
                handler () {
                    this.getJobsPaginated()
                        .then(data => {
                        this.jobs = data.jobs
                        this.totalJobs = data.totalJobs
                    })
                    deep: true
                    }
            },
        },
        mounted() {
            //If job ID 0 then no job, used for linking with customer ID set
            //Only preset customer ID if job is 0
            if (isNaN(Number(this.$route.params.id))); //Do Nothing
            else if (Number(this.$route.params.id) !== 0) this.job_id = Number(this.$route.params.id);
            else this.customer_id = Number(this.$route.params.cus);
            
        },
        methods: {
            setCustomerId(id) {
                this.customer_id = id;
            },
            goToJob(id) {
                this.$router.push('/job/' + id);
            },
            getJobsPaginated() {
                this.loading = true
                return new Promise((resolve, reject) => {
                    const { sortBy, descending, page, rowsPerPage } = this.paginationJob

                    // let jobs = new Promise(this.getAllJobs());
                    axios.post('/jobs/allJobsDetails', this.paginationJob)
                        .then((response) => {
                            let jobs = response.data.data;
                            const totalJobs = response.data.total;

                            this.loading = false
                            resolve({
                                jobs,
                                totalJobs
                            })
                        })
                        .catch((error) => {
                            console.log(error);
                        });    
                    
                })
            },
        }
    }
</script>
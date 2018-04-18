<template>
    <div>
    <v-layout row wrap v-show="job_id !== null || customer_id !== null">
        <customer-form :id.sync="customer_id"></customer-form>
    </v-layout>  
    <job-form v-show="job_id !== null || customer_id !== null" :job_id.sync="job_id" :customer_id.sync="customer_id" v-on:customerId="setCustomerId"></job-form>
    <job-list v-if="job_id == null && customer_id == null"></job-list>
    <v-card v-if="job_id == null && customer_id == null">
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
            <v-data-table v-bind:headers="jobHeaders" :items="jobs" v-bind:pagination.sync="paginationJob" class="elevation-1" :search="searchJob" :total-items="totalJobs" :loading="loading">
                <template slot="items" slot-scope="props">
                    <tr @click="goToJob(props.item.id)">
                        <td class="text-xs-center">{{ props.item.id }}</td>
                        <td class="text-xs-left">{{ props.item.estimate }}</td>
                        <td class="text-xs-left">{{ props.item.created_at }}</td>
                        <td class="text-xs-left">{{ props.item.due_date }}</td>
                        <td class="text-xs-left">{{ props.item.completed_at }}</td>                                
                    </tr>
                </template>
            </v-data-table>
    </v-card>
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
                    value: 'estimate'
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
            paginationJob: {}
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
                    this.getDataFromApi()
                        .then(data => {
                        this.jobs = data.jobs
                        this.totalJobs = data.total
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
            this.getAllJobs();
            
        },
        methods: {
            setCustomerId(id) {
                this.customer_id = id;
            },
            getAllJobs() {
                axios.post('/jobs/allJobsDetails', this.paginationJob)
                    .then((response) => {
                        return response.data.data;
                        // this.jobs = response.data.data;
                        // console.log(response.data.data);
                    })
                    .catch((error) => {
                        console.log(error);
                    });     
            },
            getDataFromApi () {
                this.loading = true
                return new Promise((resolve, reject) => {
                    const { sortBy, descending, page, rowsPerPage } = this.paginationJob

                    // let jobs = new Promise(this.getAllJobs());
                    let jobs = new Promise(function(resolve, reject) {
                            this.getAllJobs();
                        });
                    const totalJobs = jobs.length

                    if (this.paginationJob.sortBy) {
                        jobs = jobs.sort((a, b) => {
                        const sortA = a[sortBy]
                        const sortB = b[sortBy]

                        if (descending) {
                            if (sortA < sortB) return 1
                            if (sortA > sortB) return -1
                            return 0
                        } else {
                            if (sortA < sortB) return -1
                            if (sortA > sortB) return 1
                            return 0
                        }
                        })
                    }

                    if (rowsPerPage > 0) {
                        jobs = jobs.slice((page - 1) * rowsPerPage, page * rowsPerPage)
                    }

                    setTimeout(() => {
                        this.loading = false
                        resolve({
                        jobs,
                        totalJobs
                        })
                    }, 1000)
                })
            },
        }
    }
</script>
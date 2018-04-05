<template>
    <v-flex xs12 md6>
        <transition name="component-fade" appear>          
        <v-card class="ma-3">
            <v-toolbar color="indigo" dark clipped-left flat>
                <v-toolbar-title>Recent Jobs</v-toolbar-title>
            </v-toolbar>
        <v-list dense>
            <template v-for="(job, index) in jobs">
                <v-list-tile
                ripple
                :key="job.id"
                :href="'#/job/' + job.id"
                avatar
                >
                <v-list-tile-avatar>
                    <img :src="job.job_images[0].image">
                </v-list-tile-avatar>
                    <v-list-tile-title>
                            {{job.customer.fname}} {{job.customer.lname}} Job ID: {{job.id}}
                    </v-list-tile-title>
                </v-list-tile>
                <v-divider v-if="index + 1 < jobs.length" :key="job.id"></v-divider>
            </template>
        </v-list>
        </v-card>
        </transition>       
    </v-flex>
</template>

<script>
    export default {
        data: () => ({
            jobs: []
        }),
        methods: {
            getJobList() {
                axios.get('/jobs/recentJobsList')
                    .then((response) => {
                        this.jobs = response.data;
                        console.log(response.data);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },

            setId(val) {
                this.id = Number(val);
            }
        },

        props: {
            id: Number
        },
        mounted() {
            this.getJobList();
        }
    }
</script>
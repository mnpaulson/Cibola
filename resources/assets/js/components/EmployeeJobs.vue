<template>
  <v-layout row wrap xs12>
        <template v-for="(employee, emp_index) in employees">
            <v-flex d-flex xs12 sm6 md4 lg3 xl2 :key="employee.name + '-key'" v-if="selected === 0 || selected === employee.id">
                <transition name="component-fade" appear>                                    
                <v-card>
                    <v-toolbar>
                    <v-toolbar-title>{{employee.name}}</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn icon @click="toggle(employee.id)">
                        <v-icon v-if="selected === employee.id">favorite</v-icon>
                        <v-icon v-if="selected !== employee.id">favorite_border</v-icon>                        
                    </v-btn>
                    <v-toolbar-title>{{employee.jobs.length}}</v-toolbar-title>
                    </v-toolbar>
                    <v-list two-line>
                    <template v-for="(job, index) in employee.jobs">
                        <v-list-tile
                        avatar
                        ripple
                        :key="job.title"
                        @click="goTo(job.id)"
                        >
                        <v-list-tile-content>
                            <v-list-tile-title>{{ job.id }} — {{ job.customer.name }}</v-list-tile-title>
                            <v-list-tile-sub-title class="">
                                <span v-bind:class="{'vital-date': job.vital_date, 'text--primary': !job.vital_date }">
                                    Due: {{ job.due_date }}
                                </span> — For: ${{ job.estimate.toLocaleString() }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                            <v-btn color="" icon ripple @click.stop="completeToggle(job.id, emp_index, index)">
                                <v-icon v-if="!job.completed_at" class="complete-btn" color="grey lighten-1">check_circle</v-icon>
                                <v-icon v-if="job.completed_at" class="complete-btn" color="green lighten-2">check_circle</v-icon>                                
                            </v-btn>
                        </v-list-tile-action>
                        </v-list-tile>
                        <v-divider v-if="index + 1 < employee.jobs.length" :key="index"></v-divider>
                    </template>
                    </v-list>
                </v-card>
                </transition>
            </v-flex>
        </template>
  </v-layout>
</template>

<script>
    export default {
        data: () => ({
            employees: null,
            selected: 0
        }),
        methods: {
            getOutstandingJobs() {
            axios
                .get("/employees/outstandingJobs")
                .then(response => {
                    // console.log(response.data);
                    this.employees = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
            },
            toggle(id) {
                if (this.selected) {
                    this.selected = 0;
                    localStorage.setItem('employee', Number(0));
                }
                else {
                    this.selected = id;
                    localStorage.setItem('employee', Number(id));                    
                }
                console.log(localStorage.employee);                
            },
            goTo(id) {
                this.$router.push('/job/' + id);
            },
            completeToggle(id, emp_index, job_index) {
                if (!this.employees[emp_index].jobs[job_index].completed_at) {
                    axios.post('jobs/complete', {id: id})
                        .then((response) => {
                            console.log(response);
                            this.employees[emp_index].jobs[job_index].completed_at = true;
                        })
                        .catch((error) => {

                            console.log(error);
                    });
                } else {
                    axios.post('jobs/uncomplete', {id: id})
                        .then((response) => {
                            console.log(response);
                            this.employees[emp_index].jobs[job_index].completed_at = false;
                        })
                        .catch((error) => {

                            console.log(error);
                    });
                }

            }
        },
        mounted() {
            this.getOutstandingJobs();
            if (Number(localStorage.employee)) this.selected = Number(localStorage.employee);
        },
        watch: {

        }
    
    }   
</script>

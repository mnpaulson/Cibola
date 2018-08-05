<template>
<transition name="component-fade" appear>
  <v-flex d-flex class="xs12 sm12 md6 lg4 xl4">
    <v-data-table
    :headers="headers"
    :items="stats"
    hide-actions
    class="elevation-1"
    >
     <template slot="items" slot-scope="props">
         <td>{{ props.item.name }}</td>
         <td>{{ props.item.count }}</td>
         <td>${{ props.item.total }}</td>
         <td><v-tooltip right><span slot="activator">{{ props.item.wait }}</span><span>{{ props.item.days }} Days</span></v-tooltip></td>
     </template>
    </v-data-table>
  </v-flex>
</transition>
</template>

<script>
    export default {
        data: () => ({
            stats: [],
            headers: [{
                text: 'Employee',
                sortable: false,
                value: 'name'
            },
            {
                text: '# Jobs',
                sortable: false,
                value: 'count'
            },
            {
                text: '$ Outstanding',
                sortable: false,
                value: 'total'
            },
            {
                text: 'Farthest Date',
                sortable: false,
                value: 'wait'
            }]
        }),
        methods: {
            getOutstandingStats() {
            axios
                .get("/employees/outstandingStats")
                .then(response => {
                    response.data.forEach(emp => {
                        if(emp.jobs[0]) {
                            emp.wait = emp.jobs[0].due_date;
                            emp.count = emp.jobs.length;
                            emp.total = 0;
                            emp.jobs.forEach(job => {
                                emp.total += job.estimate;
                            })
                            emp.total = emp.total.toLocaleString();
                            emp.days = this.compareDate(emp.wait);
                            delete emp.jobs;
                        }
                    }); 
                    this.$set(this, 'stats', response.data);                                       
                })
                .catch(error => {
                    console.log(error);
                });
            },
            compareDate(date) {
                var year = Number(date.substring(0,4));
                var month = Number(date.substring(5,7));
                var day = Number(date.substring(8,10));
                var oneDay = 24*60*60*1000;

                var dateObj = new Date(year, month - 1, day);
                var currentDate = new Date();

                return Math.round((dateObj.getTime() - currentDate.getTime())/(oneDay));
            }
        },
        mounted() {
            this.getOutstandingStats();
        },
        watch: {

        }

    }
</script>
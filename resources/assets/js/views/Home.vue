<template>
<div>
<v-layout row wrap>
    <customer-form :id.sync="id"></customer-form>
</v-layout>
<v-layout row wrap>
    <job-lookup ></job-lookup>
</v-layout>
<v-layout row wrap class="">    
    <!-- <customer-list></customer-list>
    <job-list></job-list> -->
    <!-- <employee-jobs></employee-jobs> -->
</v-layout>
</div>
</template>

<script>
    export default {
        data: () => ({
            id: null
        }),
        methods: {
            getOutstandingJobs() {
            axios
                .get("/employees/outstandingJobs")
                .then(response => {
                    console.log(response.data);
                })
                .catch(error => {
                    console.log(error);
                });
            },
        },
        mounted() {
            // this.getOutstandingJobs();
        },
        watch: {
            id(val) {
                // if(this.id == null) this.$router.replace("/customer");            
                if(!isNaN(this.id)) this.$router.push("/customer/" + this.id);
            }
        }
    
    }
</script>
<template>
    <div>
    <v-layout row wrap v-show="job_id !== null || customer_id !== null">
        <customer-form :id.sync="customer_id"></customer-form>
    </v-layout>  
    <job-form v-show="job_id !== null || customer_id !== null" :job_id.sync="job_id" :customer_id.sync="customer_id" v-on:customerId="setCustomerId"></job-form>
    <job-list v-if="job_id == null && customer_id == null"></job-list>
    
    </div>  
</template>

<script>
    export default {
        data: () => ({
            customer_id: null,
            job_id: null
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
            }
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
            }
        }
    }
</script>
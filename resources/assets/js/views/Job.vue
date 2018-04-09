<template>
    <div>
        <customer-form :id.sync="customer_id">></customer-form>
        <job-form :job_id.sync="job_id" :customer_id.sync="customer_id" v-on:customerId="setCustomerId"></job-form>
    </div>
</template>

<script>
    export default {
        data: () => ({
            customer_id: null,
            job_id: null
        }),
        mounted() {
            //If job ID 0 then no job, used for linking with customer ID set
            //Only preset customer ID if job is 0
            if (Number(this.$route.params.id) !== 0) this.job_id = Number(this.$route.params.id);
            else this.customer_id = Number(this.$route.params.cus);
            
        },
        methods: {
            setCustomerId(id) {
                this.customer_id = id;
            }
        }
    }
</script>
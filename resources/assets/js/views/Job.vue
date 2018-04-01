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
            this.job_id = Number(this.$route.params.id);
        },
        methods: {
            setCustomerId(id) {
                this.customer_id = id;
            }
        }
    }
</script>
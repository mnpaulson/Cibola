<template>
    <div>
        <customer-form :id.sync="customer_id">></customer-form>
        <job-form :job_id.sync="job_id" :customer_id.sync="customer_id"></job-form>
    </div>
</template>

<script>
    export default {
        data: () => ({
            customer_id: null,
            job_id: null
        })
    }
</script>
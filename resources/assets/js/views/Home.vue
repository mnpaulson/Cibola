<template>
<v-layout row>
    <customer-form :id.sync="id"></customer-form>
    <job-lookup></job-lookup>
</v-layout>
</template>

<script>
    export default {
        data: () => ({
            id: null
        })
    }
</script>
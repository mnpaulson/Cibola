<template>
    <div>
    <v-layout row wrap>
        <customer-form :id.sync="customer_id"></customer-form>
    </v-layout>
    <goldcredit-form :customer_id.sync="customer_id"></goldcredit-form>
    </div>
</template>

<script>
    export default {
        data: () => ({
            customer_id: null
        })
    }
</script>
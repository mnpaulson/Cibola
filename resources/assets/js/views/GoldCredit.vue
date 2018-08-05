<template>
    <div>
    <v-layout row wrap>
        <customer-form :id.sync="customer_id"></customer-form>
    </v-layout>
    <v-layout row wrap>
        <goldcredit-form></goldcredit-form>
    </v-layout>
    </div>
</template>

<script>
    export default {
        data: () => ({
            customer_id: null
        })
    }
</script>
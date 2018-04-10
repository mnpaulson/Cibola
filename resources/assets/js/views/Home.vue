<template>
<div>
<v-layout row wrap>
    <customer-form :id.sync="id"></customer-form>
    <job-lookup></job-lookup>
</v-layout>
<v-layout row wrap class="">    
    <customer-list></customer-list>
    <job-list></job-list>
</v-layout>
</div>
</template>

<script>
    export default {
        data: () => ({
            id: null
        }),
        methods: {

        },
        watch: {
            id(val) {
                // if(this.id == null) this.$router.replace("/customer");            
                if(!isNaN(this.id)) this.$router.push("/customer/" + this.id);
            }
        }
    
    }
</script>
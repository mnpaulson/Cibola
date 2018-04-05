<template>
<div>
<v-layout row>
    <customer-form :id.sync="id"></customer-form>
    <job-lookup></job-lookup>
</v-layout>
<v-layout row class="mt-4">    
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
        watch: {
            id(val) {
                // if(this.id == null) this.$router.replace("/customer");            
                if(!isNaN(this.id)) this.$router.push("/customer/" + this.id);
            }
        }
    
    }
</script>
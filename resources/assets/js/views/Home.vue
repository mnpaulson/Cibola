<template>
<div>
<v-layout row>
    <customer-form :id.sync="id"></customer-form>
    <job-lookup></job-lookup>
    <v-flex xs12 md4>
        <v-card>
            <v-btn>New Job Bag</v-btn>
        </v-card>
    </v-flex>
</v-layout>
<v-layout row class="mt-4">    
    <customer-list></customer-list>
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
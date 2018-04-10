<template>
  <v-flex d-flex xs12 sm12 md3>
    <transition name="component-fade" appear>    
    <v-card class="">
      <!-- <v-toolbar color="indigo" dark clipped-left flat>
        <v-toolbar-title>Job Bag Lookup</v-toolbar-title>
      </v-toolbar> -->
      <v-card-text>
        <v-form @submit="searchJob">
              <v-text-field label="Job Lookup" v-model="job" xs12></v-text-field>
        </v-form>
      </v-card-text>
    </v-card>
    </transition>
  </v-flex>
</template>

<script>
  export default {
    data: () => ({
      job: null
    }),
    methods: {
      searchJob() {
        this.$router.push('job/' + this.job);
      }
    }
  }
</script>
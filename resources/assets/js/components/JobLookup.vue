<template>
  <v-flex xs12 sm12 md3>
    <transition name="component-fade" appear>    
    <v-card class="ma-3">
      <v-toolbar color="indigo" dark clipped-left flat>
        <v-toolbar-title>Job Bag Lookup</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-form>
              <v-text-field label="Job Bag Search" v-model="job" xs12 @keyup="searchJob"></v-text-field>
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
        console.log(this.job);
      }
    }
  }
</script>
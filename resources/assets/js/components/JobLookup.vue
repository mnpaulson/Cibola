<template>
  <v-flex xs12 sm6>
    <v-card>
      <v-toolbar color="indigo" dark clipped-left flat>
        <v-toolbar-title>Job Bag Lookup</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-form>
              <v-text-field label="Job Bag Search" v-model="job" xs12 @keyup="searchJob"></v-text-field>
        </v-form>
      </v-card-text>
    </v-card>
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
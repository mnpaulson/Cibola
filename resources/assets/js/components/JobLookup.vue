<template>
  <v-flex d-flex xs12 sm12 md4>
    <transition name="component-fade" appear>    
        <v-form v-model="valid" @submit="searchJob">
              <v-text-field :rules="jobRules" prepend-inner-icon="work" solo append-icon="add_circle" label="Enter Job Number" v-model="job" xs12 @click:append="newJob"></v-text-field>
        </v-form>
    </transition>
  </v-flex>
</template>

<script>
  export default {
    data: () => ({
      job: null,
      valid: false,
      jobRules: [
          v => {
              var pattern = new RegExp(/^\d*$/);
              return pattern.test(v) || "Not a valid job number";
          }
      ],
    }),
    methods: {
      searchJob() {
        if (this.valid) this.$router.push('job/' + this.job);
      },
      newJob() {
        this.$router.push('job/0/0');        
      }
    }
  }
</script>
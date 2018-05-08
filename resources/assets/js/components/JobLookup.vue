<template>
  <v-flex d-flex xs12 sm12 md4>
    <transition name="component-fade" appear>    
        <v-form @submit="searchJob">
              <v-text-field prepend-icon="work" solo label="Enter Job Number" v-model="job" xs12></v-text-field>
        </v-form>
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
        if (Number.isInteger(parseInt(this.job))) {
          this.$router.push('job/' + this.job);
        }
      }
    }
  }
</script>
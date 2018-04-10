<template>
    <v-flex xs12 md6>
        <transition name="component-fade" appear>          
        <v-card class="">
            <!-- <v-toolbar color="indigo" dark clipped-left flat>
                <v-toolbar-title>Recent Jobs</v-toolbar-title>
            </v-toolbar> -->
        <v-list dense>
            <template v-for="(job, index) in jobs">
                <v-list-tile
                ripple
                :key="job.id + '-key'"
                :href="'#/job/' + job.id"
                avatar
                >
                <v-list-tile-avatar>
                    <img v-if="job.job_images[0]" :src="job.job_images[0].image">
                </v-list-tile-avatar>
                <v-list-tile-content>
                    <v-list-tile-title>
                            {{job.customer.fname}} {{job.customer.lname}}
                    </v-list-tile-title>
                    <v-list-tile-sub-title class="text--primary">Job #{{ job.id }} Due: {{job.due_date}}</v-list-tile-sub-title>
                    <v-list-tile-sub-title>Est: ${{ job.estimate }}</v-list-tile-sub-title>
                </v-list-tile-content>
                </v-list-tile>
                <v-divider v-if="index + 1 < jobs.length" :key="job.id"></v-divider>
            </template>
        </v-list>
        </v-card>
        </transition>       
    </v-flex>
</template>

<script>
export default {
  data: () => ({
    jobs: []
  }),
  methods: {
    getJobList() {
      axios
        .get("/jobs/recentJobsList")
        .then(response => {
          this.jobs = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    },

    setId(val) {
      this.id = Number(val);
    }
  },

  props: {
    id: Number
  },
  mounted() {
    this.getJobList();
  }
};
</script>
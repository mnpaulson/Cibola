<template>
<!-- <div>
<v-alert
:type="type"
dismissible
v-model="status"
outline
transition="slide-y-transition"
>
{{ msg }}
</v-alert>
</div> -->
<v-snackbar
  :timeout=5000
  top
  v-model="status"
  :color=type
>
  {{ msg }}
  <v-btn dark flat  @click.native="status = false">Close</v-btn>
</v-snackbar>
</template>


<script>
export default {
  data: () => ({}),
  watch: {
    // $route(to, from) {
    //   //Hide message 5 seconds after a route change if its not an error
    //   if (this.type != "error") setTimeout(()=>{ this.status = false; }, 5000);
    // }
  },
  methods: {},
  computed: {
    status: {
      get: function() {
        return this.$root.$data.store.alert.status;
      },
      set: function(val) {
        this.$root.$data.store.alert.status = val;
      }
    },
    msg() {
      return this.$root.$data.store.alert.msg;
    },
    type() {
      return this.$root.$data.store.alert.type;
    }
  }
};
</script>

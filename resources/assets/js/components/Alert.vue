<template>
<div>
<v-alert
:type="type"
dismissible
v-model="status"
outline
transition="slide-y-transition"
>
{{ msg }}
</v-alert>
</div>
</template>


<script>
export default {
  data: () => ({
  }),
  watch: {
      
  },
  methods: {

  },
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
}
</script>

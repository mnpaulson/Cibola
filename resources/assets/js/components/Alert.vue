<template>
<div>
<v-alert
type="success"
dismissible
:value="saveSuccess"
transition="scale-transition"
>
{{ successMessage }}
</v-alert>
</div>
</template>


<script>
export default {
  data: () => ({
    sharedStore: store.alert       
  }),
  watch: {

  },
  methods: {

  }
}
</script>

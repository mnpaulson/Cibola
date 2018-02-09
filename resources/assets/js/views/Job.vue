<template>
    <div>
        <customer-form :id.sync="id">></customer-form>
        <job-form></job-form>
    </div>
</template>

<script>
    export default {
        data: () => ({
            id: null
        })
    }
</script>
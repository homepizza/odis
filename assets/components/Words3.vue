<template>
    <p style="text-align: center" v-if="count <= 17">{{ message }}</p>
</template>

<script>
    export default {
        name: "words3",
        data() {
            return {
                message: 'Текст который изменит состояние'
            }
        },
        computed: {
            count() {
                console.log('123');
                return this.$store.state.count;
            }
        }
    }
</script>

<style>
</style>
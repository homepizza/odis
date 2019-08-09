<template>
    <p style="text-align: center;" v-bind:style="{background: color, width: '280px', margin: '0 auto'}">
        {{ message }}
    </p>
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
            },
            color() {
                return this.$store.state.count < 17 ? 'white' :  '#1A83D2';
            }
        }
    }
</script>

<style>
</style>
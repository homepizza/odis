<template>
    <div class="center">
        <h2 class="center">My Application</h2>
        <div v-text="message"></div>
        {{ message }}
        <ul>
            <li :key="word.id" v-for="word in words">{{ word }}</li>
        </ul>
        <p>Текст теста</p>
        <p>{{ count }}</p>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                message: "A list of words",
                words: []
            };
        },
        mounted() {
            let el = document.querySelector("div[data-words]");
            let mywords = el.dataset.words.split(",");
            this.words.push.apply(this.words, mywords);
        },
        computed: {
            count() {
                return this.$store.state.count;
            }
        }
    };
</script>

<style>
    .center {
        text-align: center;
    }
</style>
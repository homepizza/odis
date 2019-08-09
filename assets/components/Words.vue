<template>
    <div class="center">
        <h2 class="center">My Application</h2>
        <div v-text="message"></div>
        {{ message }}
        <ul>
            <li :key="word.id" v-for="word in words">{{ word }}</li>
        </ul>
        <button @click="Test()">Тест</button>
    </div>
</template>

<script>
    export default {
        name: 'words',
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
        methods: {
            Test: function () {
                console.log('TEST');
                this.$store.commit('increment');
            }
        }
    };
</script>

<style>
    .center {
        text-align: center;
    }
</style>
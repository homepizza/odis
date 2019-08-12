import Vue from 'vue';
import Words from "./components/Words";
import store from "./store/store";

Vue.component('words', Words);

new Vue({el: '#app',
    store,
    methods: {
        say: function () {
            console.log(this.$store.state.NewTask.count);
        }
    }
});
import Vue from 'vue';
import Vuex from 'vuex';
import Words from './components/Words';
import Words2 from "./components/Words2";
import Words3 from "./components/Words3";

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        count: 0
    },
    mutations: {
        increment (state) {
            state.count++
        }
    }
});

Vue.component('words', Words);
Vue.component('w2', Words2);
Vue.component('words3', Words3);

new Vue({el: '#app', store});
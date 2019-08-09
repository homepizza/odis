import Vue from 'vue';
import Vuex from 'vuex';
import Words from './components/Words';

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

new Vue({el: '#app', store});
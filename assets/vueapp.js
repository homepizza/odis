import Vue from 'vue';
import Words from './components/Words';
import Words2 from "./components/Words2";

Vue.component('words', Words);
Vue.component('w2', Words2);

new Vue({el: '#app'});
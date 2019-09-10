import Vue from 'vue';
import Vuex from 'vuex';
import Task from "./global/Task";
import Tasks from "./global/Tasks";
import Profile from "./global/Profile";
import { mapState } from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        Task,
        Tasks,
        Profile
    }
});
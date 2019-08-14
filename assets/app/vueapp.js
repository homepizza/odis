import Vue from 'vue';
import Axios from 'axios';
import vSelect from 'vue-select';
import moment from "moment";
import store from "./store/store";
import DetailsCreate from "./components/DetailsCreate";
import TaskCreate from "./components/TaskCreate";
import Comments from "./components/Comments";
import TaskMembers from "./components/TaskMembers";
import Task from "./components/Task";

Vue.prototype.$http = Axios;
Vue.component('v-select', vSelect);
Vue.component('details-create', DetailsCreate);
Vue.component('task-create', TaskCreate);
Vue.component('comments', Comments);
Vue.component('task-members', TaskMembers);
Vue.component('task', Task);

Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('DD.MM.YYYY HH:mm')
    }
});

new Vue({el: '#app',
    store,
    methods: {
        createTask: function () {
            let task = store.getters.getTask;
            this.$http.post('/tasks/new', task).then(response => {
                if (response.status === 200) {
                    window.open('/tasks', '_self');
                }
            });
        },
        editTask: function () {
            let editMode = store.state.Task.edit;
            store.commit('setEdit', !editMode);
        },
        saveTask: function () {
            this.editTask();
        }
    },
    computed: {
        accessCreateTask() {
            let task = store.state.Task;
            return task.title && task.description && task.priority && task.type && task.area;
        }
    }
});
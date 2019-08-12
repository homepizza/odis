import Vue from 'vue';
import Axios from 'axios';
import vSelect from 'vue-select';
import store from "./store/store";
import DetailsCreate from "./components/DetailsCreate";
import TaskCreate from "./components/TaskCreate";

Vue.prototype.$http = Axios;
Vue.component('v-select', vSelect);
Vue.component('details-create', DetailsCreate);
Vue.component('task-create', TaskCreate);

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

        }
    },
    computed: {
        accessCreateTask() {
            let task = store.state.Task;
            return task.title && task.description && task.priority && task.type && task.area;
        }
    }
});
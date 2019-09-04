import Vue from 'vue';
import Axios from 'axios';
import vSelect from 'vue-select';
import moment from "moment";
import VueSweetalert2 from "vue-sweetalert2";
import Datepicker from 'vue2-datepicker';
import VueTimepicker from 'vue2-timepicker';
import store from "./store/store";
import DetailsCreate from "./components/DetailsCreate";
import TaskCreate from "./components/TaskCreate";
import Comments from "./components/Comments";
import TaskMembers from "./components/TaskMembers";
import Task from "./components/Task";
import Details from "./components/Details";
import Tasks from "./components/Tasks";
import Filters from "./components/Filters";
import TaskHistory from "./components/TaskHistory";

Vue.prototype.$http = Axios;
Vue.component('v-select', vSelect);
Vue.component('details-create', DetailsCreate);
Vue.component('task-create', TaskCreate);
Vue.component('comments', Comments);
Vue.component('task-members', TaskMembers);
Vue.component('task', Task);
Vue.component('task-details', Details);
Vue.component('tasks', Tasks);
Vue.component('filters', Filters);
Vue.component('task-history', TaskHistory);

Vue.use(VueSweetalert2);
Vue.use(Datepicker);
Vue.use(VueTimepicker);
Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('DD.MM.YYYY HH:mm')
    }
});
Vue.filter('formatDueDate', function(value) {
    if (value) {
        return moment(String(value)).format('DD.MM.YYYY')
    }
});

new Vue({el: '#app',
    store,
    data: function() {
        return {
            saved: false,
            search: ''
        }
    },
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
            let task = store.getters.getTask;
            this.$http.put('/tasks/' + task.taskNumber + '/update', task).then(response => {
                if (response.status === 200) {
                    store.commit('setWorkflow', true);
                    store.commit('setTaskHistory', true);
                    this.$swal({
                        position: 'top',
                        type: 'success',
                        title: 'Сохранено!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        },
        saveProfile: function () {
            this.$swal({
                position: 'center',
                type: 'success',
                title: 'Сохранено!',
                showConfirmButton: false,
                timer: 1500
            });
        }
    },
    computed: {
        accessCreateTask() {
            let task = store.state.Task;
            return task.title && task.description && task.priority && task.type && task.area;
        },
        accessSaveProfile() {
            return true;
        }
    }
});
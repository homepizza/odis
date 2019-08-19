<template>
    <table class="c-table">

        <caption class="c-table__title">
            Список задач <small>Всего задач: {{ number }}</small>

            <a class="c-table__title-action" style="display: none;">
                <i class="fa fa-cloud-download"></i>
            </a>
        </caption>

        <thead class="c-table__head c-table__head--slim">
            <tr class="c-table__row">
                <th class="c-table__cell c-table__cell--head asLink">
                    <a @click="sortNumber" class="c-nav__link">
                        Номер
                        <i class="fa" :class="{
                            'fa-sort-amount-desc': !sortNumberState,
                            'fa-sort-amount-asc': sortNumberState
                        }">
                        </i>
                    </a>
                </th>
                <th class="c-table__cell c-table__cell--head no-sort">Задача</th>
                <th class="c-table__cell c-table__cell--head asLink">
                    <a @click="sortDueDate" class="c-nav__link">
                        Срок
                        <i class="fa" :class="{
                            'fa-sort-amount-desc': !sortDueDateState,
                            'fa-sort-amount-asc': sortDueDateState
                        }">
                        </i>
                    </a>

                </th>
                <th class="c-table__cell c-table__cell--head no-sort">Исполнитель</th>
                <th class="c-table__cell c-table__cell--head no-sort">Автор</th>
                <th class="c-table__cell c-table__cell--head no-sort">Статус</th>
                <th class="c-table__cell c-table__cell--head">Приоритет</th>
                <th class="c-table__cell c-table__cell--head no-sort">Тип</th>
                <th class="c-table__cell c-table__cell--head no-sort">Направление</th>
            </tr>
        </thead>

        <tbody>
            <tr class="c-table__row" :class="task.priority.tableClass" v-for="task in searchTasks">
                <td class="c-table__cell">
                    <small class="u-block u-text-mute">{{ task.id }}</small>
                </td>
                <td class="c-table__cell" style="white-space: normal;">
                    <a :href="'/' + 'tasks' + '/' +task.id">{{ task.title }}</a>
                    <small class="u-block u-text-mute"></small>
                </td>

                <td class="c-table__cell">
                    <span v-if="task.dueDate">
                        {{ task.dueDate | formatDueDate }}
                    </span>
                    <small class="u-block u-text-danger"></small>
                </td>

                <td class="c-table__cell">
                    <div class="o-media">
                        <div v-if="task.asignee" class="o-media__img u-mr-xsmall">
                            {{ task.asignee.username }}
                        </div>
                    </div>
                </td>

                <td class="c-table__cell">
                    {{ task.author.username }}
                </td>

                <td class="c-table__cell">
                    <i class="fa fa-circle-o u-mr-xsmall" :class="task.status.class"></i>{{ task.status.name }}
                </td>
                <td class="c-table__cell">
                    <span class="c-badge" :class="task.priority.class">{{ task.priority.name }}</span>
                </td>
                <td class="c-table__cell">
                    <span class="c-badge c-badge--success">{{ task.type.name }}</span>
                </td>
                <td class="c-table__cell">
                    <span class="c-badge c-badge--primary">{{ task.area.name }}</span>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    import Moment from 'moment';
    import { extendMoment } from 'moment-range';
    const moment = extendMoment(Moment);

    export default {
        name: "Tasks",
        props: ['search'],
        data: function () {
            return {
                tasks: [],
                number: 0,
                sortNumberState: false,
                sortDueDateState: false

            }
        },
        computed: {
             searchTasks: function () {
                 let filters = this.$store.state.Tasks.applyFilters;
                 if (filters) {
                     let filtersState = this.$store.getters.getFilters;
                     this.$store.commit('setApplyFilters', false);
                     return this.tasks.filter(task => {
                         let author, asignee, priority, type, area, due = true;
                         if (filtersState.author !== null && filtersState.author.hasOwnProperty('id')) {
                            author = task.author.hasOwnProperty('id')
                                ? task.author.id === filtersState.author.id
                                : false;
                         } else { author = true; }
                         if (filtersState.asignee !== null && filtersState.asignee.hasOwnProperty('id')) {
                             asignee = task.asignee.hasOwnProperty('id')
                                 ? task.asignee.id === filtersState.asignee.id
                                 : false;
                         } else { asignee = true; }
                         if (filtersState.priority !== null && filtersState.priority.hasOwnProperty('id')) {
                             priority = task.priority.hasOwnProperty('id')
                                 ? task.priority.id === filtersState.priority.id
                                 : false;
                         }
                         else { priority = true; }
                         if (filtersState.type !== null && filtersState.type.hasOwnProperty('id')) {
                              type = task.type.hasOwnProperty('id')
                                 ? task.type.id === filtersState.type.id
                                 : false;
                         } else { type = true; }
                         if (filtersState.area !== null && filtersState.area.hasOwnProperty('id')) {
                             area = task.area.hasOwnProperty('id')
                                 ? task.area.id === filtersState.area.id
                                 : false;
                         } else { area = true; }
                         let dueFromNull = filtersState.dueFrom !== null;
                         let dueToNull  =  filtersState.dueTo !== null;
                         let dueDateNull = task.dueDate !== null;
                         if (dueFromNull && dueToNull && (filtersState.dueFrom.length > 0) && (filtersState.dueTo.length > 0)) {
                             if (dueDateNull && task.dueDate.length > 0) {
                                var startDate = new Date(filtersState.dueFrom);
                                var endDate = new Date(filtersState.dueTo);
                                var date = new Date(task.dueDate);
                                var range = moment.range(startDate, endDate);
                                due = range.contains(date);
                             } else { due = false; }
                         } else if (dueFromNull && filtersState.dueFrom.length > 0) {
                                if (dueDateNull && task.dueDate.length > 0) {
                                    due = moment(task.dueDate).isAfter(filtersState.dueFrom);
                                } else { due = false; }
                         } else if (dueToNull && filtersState.dueTo.length > 0)  {
                             if (dueDateNull && task.dueDate.length > 0) {
                                    due = moment(task.dueDate).isBefore(filtersState.dueTo);
                             } else { due = false; }
                         } else { due = true; }

                         let result = author && asignee && priority && type && area && due;
                         return result;
                     });
                 } else {
                     return this.tasks.filter(task => {
                         let title = task.title.indexOf(this.search) !== -1;
                         let author = task.author.username.indexOf(this.search) !== -1;
                         if (task.asignee === null) { task.asignee = {username: ''}; }
                         let asignee = task.asignee.username.indexOf(this.search) !== -1;
                         return title || author || asignee;
                     });
                 }
             }
        },
        beforeMount() {
            this.$http.get('/tasks/data').then(response => {
                this.tasks = response.data.tasks;
                this.number = response.data.number;
            });
        },
        methods: {
            sortNumber: function () {
                let sorted = this.sortNumberState;
                this.tasks.sort(function (obj, obj1) {
                    let result = sorted ? obj.id - obj1.id : obj1.id - obj.id;
                    return result;
                });
                this.sortNumberState = !this.sortNumberState;
                this.sortDueDateState = false;
            },
            sortDueDate: function () {
                let sorted = this.sortDueDateState;
                this.tasks.sort(function (first, second) {
                    let dateFirst = new Date(first.dueDate);
                    let dateSecond = new Date(second.dueDate);
                    let result = sorted
                        ? dateFirst - dateSecond
                        : dateSecond - dateFirst;
                    return result;
                });
                this.sortDueDateState = !this.sortDueDateState;
                this.sortNumberState = false;
            }
        }
    }
</script>

<style>
    .asLink {
        cursor: pointer;
    }
</style>
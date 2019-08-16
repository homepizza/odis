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
                            'fa-sort-amount-desc': !$store.state.Tasks.sortNumber,
                            'fa-sort-amount-asc': $store.state.Tasks.sortNumber
                        }">
                        </i>
                    </a>
                </th>
                <th class="c-table__cell c-table__cell--head no-sort">Задача</th>
                <th class="c-table__cell c-table__cell--head asLink">
                    <a @click="sortDueDate" class="c-nav__link">
                        Срок
                        <i class="fa" :class="{
                            'fa-sort-amount-desc': !$store.state.Tasks.sortDueDate,
                            'fa-sort-amount-asc': $store.state.Tasks.sortDueDate
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
            <tr class="c-table__row" :class="task.priority.tableClass" v-for="task in tasks">
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
    export default {
        name: "Tasks",
        data: function () {
            return {
                tasks: [],
                number: 0,
                sortNumberState: false,
                sortDueDateState: false
            }
        },
        created() {
            this.$http.get('/tasks/data').then(response => {
                this.tasks = response.data.tasks;
                this.number = response.data.number;
            });
        },
        methods: {
            sortNumber: function () {
                this.sortNumberState = !this.sortNumberState;
                this.$store.commit('setSortNumber', this.sortNumberState);
                this.$store.commit('setSortDueDate', false);
            },
            sortDueDate: function () {
                this.sortDueDateState = !this.sortDueDateState;
                this.$store.commit('setSortDueDate', this.sortDueDateState);
                this.$store.commit('setSortNumber', false);
            }
        }
    }
</script>

<style>
    .asLink {
        cursor: pointer;
    }
</style>
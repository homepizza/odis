<template>
    <div class="filter">
        <div class="filter-panel" v-show="openFilter">
            <div class="filters">
                <label class="c-field__label">Готовые фильтры</label>
                <v-select label="name"
                          @input="setFilter"
                          :options="filters"
                          v-model="filter"
                >
                </v-select>
                <label class="c-field__label">Автор</label>
                <v-select label="fullname"
                          :options="users"
                          v-model="author"
                >
                </v-select>
                <label class="c-field__label">Исполнитель</label>
                <v-select label="fullname"
                          :options="users"
                          v-model="asignee"
                >
                </v-select>
                <label class="c-field__label">Статус</label>
                <v-select label="name"
                          :options="statuses"
                          v-model="status"
                >
                </v-select>
                <label class="c-field__label">Приоритет</label>
                <v-select label="name"
                          :options="priorities"
                          v-model="priority"
                >
                </v-select>
                <label class="c-field__label">Тип</label>
                <v-select label="name"
                          :options="types"
                          v-model="type"
                >
                </v-select>
                <label class="c-field__label">Направление</label>
                <v-select label="name"
                          :options="areas"
                          v-model="area"
                >
                </v-select>
                <label class="c-field__label">Срок от</label>
                <date-picker v-model="dueFrom"
                             :first-day-of-week="1"
                             @input="date2valueFrom"
                             valueType="format"
                             type="date"
                             format="DD.MM.YYYY"
                             lang="ru"
                             width="236px"
                >
                </date-picker>
                <label class="c-field__label">Срок до</label>
                <date-picker v-model="dueTo"
                             :first-day-of-week="1"
                             @input="date2valueTo"
                             valueType="format"
                             type="date"
                             format="DD.MM.YYYY"
                             lang="ru"
                             width="236px"
                >
                </date-picker>
                <div class="row filters-btn">
                    <button class="c-btn c-btn--info" v-if="applyButton" @click="applyFilters">
                        Применить
                    </button>
                    <div v-if="saveButton">
                        <button class="c-btn c-btn--success" :disabled="!filterName" @click="saveFilter">Сохранить фильтр</button>
                        <input v-model="filterName" type="text" class="c-input" placeholder="Дайте название фильтру">
                    </div>
                </div>
            </div>
        </div>
        <div class="filter-icon" @click="openFilters">
            <i class="fa" :class="{'fa-filter': !openFilter, 'fa-caret-left': openFilter}"></i>
        </div>
    </div>
</template>

<script>
    import Datepicker from 'vue2-datepicker'
    import moment from 'moment'

    export default {
        name: "Filters",
        data: function () {
            return {
                openFilter: false,
                filters: [],
                filter: {},
                filterName: '',
                applyBtn: false,
                users: [],
                author: {},
                asignee: {},
                dueFrom: '',
                dueTo: '',
                priorities: [],
                priority: {},
                types: [],
                type: {},
                areas: [],
                area: {},
                statuses: [],
                status: {}
            }
        },
        beforeMount() {
            this.$http.get('/filters').then(response => {
                this.filters = response.data;
            });
            this.$http.get('/filters/users').then(response => {
                this.users = response.data;
            });
            this.$http.get('/filters/priorities').then(response => {
                this.priorities = response.data;
            });
            this.$http.get('/filters/statuses').then(response => {
                this.statuses = response.data;
            });
            this.$http.get('/task/types').then(response => {
                this.types = response.data;
            });
            this.$http.get('/task/areas').then(response => {
                this.areas = response.data;
            });
        },
        computed: {
            applyButton: function () {
                return !this.$store.state.Tasks.applyFilters;
            },
            saveButton: function () {
                let filterStatus = (this.filter !== null) ? this.filter.hasOwnProperty('name') : false;
                return this.applyBtn && (!filterStatus);
            }
        },
        methods: {
            saveFilter: function () {
                this.applyBtn = false;
                let filters = this.$store.getters.getFilters;
                filters.name = this.filterName;
                this.$http.post('/filters/save', filters).then(response => {
                    if (response.status === 200) {
                        // this.filters.push(response.data); // Почему то не работает, ... =(
                        this.$http.get('/filters').then(response => {
                            this.filters = response.data;
                        });
                    }
                });
            },
            openFilters: function () {
                this.openFilter = !this.openFilter;
            },
            setFilter: function () {
                if (this.filter !== null) {
                    this.author = this.filter.author;
                    this.asignee = this.filter.asignee;
                    this.priority = this.filter.priority;
                    this.type = this.filter.type;
                    this.area = this.filter.area;
                    this.dueFrom = this.filter.dueFrom ;
                    this.dueTo = this.filter.dueTo;
                    this.status = this.filter.status;
                } else {
                    this.author = {};
                    this.asignee = {};
                    this.priority = {};
                    this.type = {};
                    this.area = {};
                    this.dueFrom = '';
                    this.dueTo = '';
                    this.status = {};
                }
                this.applyBtn = false;
            },
            applyFilters: function() {
                let data = {
                    author: this.author,
                    asignee: this.asignee,
                    priority: this.priority,
                    type: this.type,
                    area: this.area,
                    dueFrom: this.dueFrom,
                    dueTo: this.dueTo,
                    apply: true,
                    status: this.status
                };
                this.applyBtn = true;
                this.$store.dispatch('setFilters', data);
            },
            date2valueFrom: function (sourceValue) {
                if (sourceValue) {
                    let value = moment(sourceValue, "DD.MM.YYYY").format('YYYY-MM-DDTHH:mm:ss.SSS');
                    this.$store.commit('setFilterDueFrom', value);
                }
                else {
                    this.$store.commit('setFilterDueFrom', '');
                }
            },
            date2valueTo: function (sourceValue) {
                if (sourceValue) {
                    let value = moment(sourceValue, "DD.MM.YYYY").format('YYYY-MM-DDTHH:mm:ss.SSS');
                    this.$store.commit('setFilterDueTo', value);
                }
                else {
                    this.$store.commit('setFilterDueTo', '');
                }
            },
        }
    }
</script>

<style>
    .filters {
        width: 236px;
        margin: 0 auto;
        padding-top: 24px;
        padding-bottom: 25px;
    }
    .filter {
        z-index: 1;
        position: fixed;
        margin-top: 12%;
        display: flex;
    }
    .filter-panel {
        background-color: #fff;
        border: 1px solid #e6eaee;
        width: 274px;
        height: 678px;
    }
    .filter-icon {
        cursor: pointer;
        background: linear-gradient(180deg,#fff,#f2f4f7);
        border-radius: 3px;
        padding: 10px;
        margin-left: -2px;
        height: 45px;
        z-index: -1;
    }
    .filters-btn {
        margin: 0 auto;
    }
    .filters-btn button {
        margin-top: 10px;
    }
    .filters-btn input {
        margin-top: 10px;
    }
</style>
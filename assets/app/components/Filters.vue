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
                area: {}
            }
        },
        beforeMount() {
            this.$http.get('/filters/users').then(response => {
                this.users = response.data;
            });
            this.$http.get('/filters/priorities').then(response => {
                this.priorities = response.data;
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
                return this.applyBtn && (!this.filter.hasOwnProperty('name'));
            }
        },
        methods: {
            saveFilter: function () {
                this.applyBtn = false;
                // TODO: Запрос на сохранение фильтров (getter of Store) + push в Список готовых филтров
                let filters = this.$store.getters.getFilters;
                filters.name = this.filterName;
                this.$http.post('/filters/save', filters).then(response => {
                    console.log(response.data);
                    // PUSH
                });
            },
            openFilters: function () {
                this.openFilter = !this.openFilter;
            },
            setFilter: function () {
                // TODO: назначает модели (главную filter и параметров)
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
                    apply: true
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
        margin-top: 15%;
        display: flex;
    }
    .filter-panel {
        background-color: #fff;
        border: 1px solid #e6eaee;
        width: 274px;
        height: 650px;
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
<template>
    <div class="c-card u-p-medium u-mb-medium">
        <h5 class="u-h6 u-mb-medium">Детали задачи</h5>

        <table class="u-width-100">
            <tbody>
            <tr>
                <td class="u-pb-xsmall u-color-primary u-text-small">Автор</td>
                <td class="u-pb-xsmall u-text-right u-text-mute u-text-small">
                    {{ $store.state.Task.author.fullname }}
                </td>
            </tr>
            <tr>
                <td class="u-pb-xsmall u-color-primary u-text-small">Исполнитель</td>
                <td class="u-pb-xsmall u-text-right u-text-mute u-text-small">
                    <span v-if="(!$store.state.Task.edit || $store.state.Task.isAuthor) && $store.state.Task.asignee">
                        {{ $store.state.Task.asignee.fullname }}
                    </span>
                    <div v-if="$store.state.Task.edit && !$store.state.Task.isAuthor" style="width: 212px; float: right">
                        <v-select label="fullname" @input="setAsignee" :options="developers" v-model="asignee"></v-select>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="u-pb-xsmall u-color-primary u-text-small">Статус</td>
                <td class="u-pb-xsmall u-text-right u-text-mute u-text-small">
                    <p class="u-hidden-visually">{{ needUpdate }}</p>
                    <span v-if="!$store.state.Task.edit">
                        <i class="fa fa-circle-o" v-bind:class="$store.state.Task.status.class"></i>
                        {{ $store.state.Task.status.name }}
                    </span>
                    <div style="width: 212px; float: right" v-else>
                        <v-select label="name" @input="setStatus" :options="statuses" v-model="status"></v-select>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="u-color-primary u-text-small">Приоритет</td>
                <td class="u-text-right u-text-mute u-text-small">
                    <span class="c-badge c-badge--xsmall"
                          v-if="!$store.state.Task.edit"
                          v-bind:class="$store.state.Task.priority.class"
                    >
                        {{ $store.state.Task.priority.name }}
                    </span>
                    <div style="width: 212px; float: right"
                         v-else-if="!($store.state.Task.hasWork && $store.state.Task.isAuthor)"
                    >
                        <v-select label="name" @input="setPriority" :options="priorities" v-model="priority"></v-select>
                    </div>
                    <span class="c-badge c-badge--xsmall"
                          v-bind:class="$store.state.Task.priority.class"
                          v-else
                    >
                        {{ $store.state.Task.priority.name }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="u-color-primary u-text-small">Тип</td>
                <td class="u-text-right u-text-mute u-text-small">
                    <span v-if="!$store.state.Task.edit" class="c-badge c-badge--success c-badge--xsmall">
                        {{ $store.state.Task.type.name }}
                    </span>
                    <div style="width: 212px; float: right" v-else>
                        <v-select label="name" @input="setType" :options="types" v-model="type"></v-select>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="u-color-primary u-text-small">Направление</td>
                <td class="u-text-right u-text-mute u-text-small">
                    <span v-if="!$store.state.Task.edit" class="c-badge c-badge--primary c-badge--xsmall">
                        {{ $store.state.Task.area.name }}
                    </span>
                    <div style="width: 212px; float: right" v-else>
                        <v-select label="name" @input="setArea" :options="areas" v-model="area"></v-select>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="c-divider u-mv-small"></div>

        <div class="u-flex u-justify-between u-align-items-center">
            <p>Срок выполнения</p>

            <div style="display: flex;" v-if="!$store.state.Task.edit || $store.state.Task.isAuthor">
                <a :href="$store.state.Task.solutionLink"
                   v-if="$store.state.Task.solutionLink"
                   class="c-nav__link" style="margin-right: 10px; padding-top: 3px;"
                   target="_blank"
                >
                    <i class="fa fa-link"></i>
                    Решение
                </a>
                <span class="c-badge c-badge--danger" v-if="$store.state.Task.dueDate">
                    {{ $store.state.Task.dueDate | formatDueDate }}
                </span>
                <span class="c-badge c-badge--danger" v-else>Не назначен</span>
            </div>
            <div style="display: flex;" v-else-if="!$store.state.Task.isAuthor">
                <div v-if="!linkInput">
                    <a @click="linkToggle" class="c-nav__link" style="margin-right: 5px; padding-top: 7px;">
                        <i class="fa fa-link"></i>
                    </a>
                    <date-picker v-model="dueDate"
                                 :first-day-of-week="1"
                                 @input="date2value"
                                 valueType="format"
                                 type="date"
                                 format="DD.MM.YYYY"
                                 lang="ru"
                                 width="215px"
                    >
                    </date-picker>
                </div>
                <div v-else>
                    <a @click="linkToggle" class="c-nav__link"
                       style="margin-right: 10px; padding-top: 7px; float: left;"
                    >
                        <i class="fa fa-calendar"></i>
                    </a>
                    <input v-model="solutionLink"
                           @input="setSolutionLink"
                           type="text"
                           class="c-input"
                           style="width: 215px; height: 38px;"
                           placeholder="Ссылка на решение"
                    >
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import Datepicker from 'vue2-datepicker'
    import moment from 'moment'

    export default {
        name: "Details",
        components: {
            Datepicker
        },
        data: function () {
            return {
                developers: [],
                statuses: [],
                priorities: [],
                types: [],
                areas: [],
                taskNumber: '',
                author: {},
                asignee: {},
                status: {},
                type: {},
                area: {},
                priority: {},
                dueDate: '',
                solutionLink: '',
                linkInput: false
            }
        },
        created() {
            this.taskNumber = document.querySelector("span[task-number]").innerHTML;
            this.$http.get('/task/' + this.taskNumber + '/data').then(response => {
                if (response.status === 200) {
                    let task = response.data.task;
                    this.author = task.author;
                    this.asignee = task.asignee;
                    this.status = task.status;
                    this.type = task.type;
                    this.area = task.area;
                    this.priority = task.priority;
                    this.dueDate = moment(String(task.dueDate)).format('DD.MM.YYYY');
                    this.solutionLink = task.solutionLink;
                    this.loadData();
                }
            });
        },
        computed: {
            needUpdate() {
                this.loadWorkflow();
                this.$store.commit('setWorkflow', false);
                return this.$store.state.Task.workflow;
            }
        },
        methods: {
            linkToggle: function () {
                this.linkInput = !this.linkInput;
            },
            date2value: function (sourceValue) {
                if (sourceValue) {
                    let value = moment(sourceValue, "DD.MM.YYYY").format('YYYY-MM-DDTHH:mm:ss.SSS');
                    this.$store.commit('setDueDate', value);
                }
                else {
                    this.$store.commit('setDueDate', '');
                }

            },
            loadData: function () {
                this.$http.get('/task/priorities').then(response => (
                    this.priorities = response.data
                ));
                this.$http.get('/task/types').then(response => (
                    this.types = response.data
                ));
                this.$http.get('/task/areas').then(response => (
                    this.areas = response.data
                ));
                this.$http.get('/task/developers').then(response => (
                    this.developers = response.data
                ));
                this.loadWorkflow();
            },
            loadWorkflow: function () {
                this.$http.get('/workflow/status/' + this.status.id).then(response => (
                    this.statuses = response.data
                ));
            },
            setAsignee: function () {
                this.$store.commit('setAsignee', this.asignee);
            },
            setStatus: function () {
                this.$store.commit('setStatus', this.status)
            },
            setType: function () {
                this.$store.commit('setType', this.type);
            },
            setArea: function () {
                this.$store.commit('setArea', this.area);
            },
            setPriority: function () {
                this.$store.commit('setPriority', this.priority);
            },
            setSolutionLink: function () {
                this.$store.commit('setSolutionLink', this.solutionLink);
            }
        }
    }
</script>

<style>

</style>
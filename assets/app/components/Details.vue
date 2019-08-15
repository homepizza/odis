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
                    <span class="c-badge c-badge--xsmall" v-bind:class="$store.state.Task.priority.class">
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

            <div style="display: flex;">
                <a class="c-nav__link" style="margin-right: 10px; padding-top: 3px;">
                    <i class="fa fa-link"></i>
                    Решение
                </a>
                <span class="c-badge c-badge--danger" v-if="$store.state.Task.dueDate">
                    {{ $store.state.Task.dueDate | formatDueDate }}
                </span>
                <span class="c-badge c-badge--danger" v-else>Не назначен</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Details",
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
                area: {}

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
                    this.loadData();
                }
            });
        },
        methods: {
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
            }
        }
    }
</script>

<style scoped>

</style>
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
                    {{ $store.state.Task.asignee.fullname }}
                </td>
            </tr>
            <tr>
                <td class="u-pb-xsmall u-color-primary u-text-small">Статус</td>
                <td class="u-pb-xsmall u-text-right u-text-mute u-text-small">
                    <i class="fa fa-circle-o u-color-warning"></i>
                    Согласование
                </td>
            </tr>
            <tr>
                <td class="u-color-primary u-text-small">Приоритет</td>
                <td class="u-text-right u-text-mute u-text-small">
                    <span class="c-badge c-badge--warning c-badge--xsmall">Средний</span>
                </td>
            </tr>
            <tr>
                <td class="u-color-primary u-text-small">Тип</td>
                <td class="u-text-right u-text-mute u-text-small">
                    <span class="c-badge c-badge--success c-badge--xsmall">Доработка</span>
                </td>
            </tr>
            <tr>
                <td class="u-color-primary u-text-small">Направление</td>
                <td class="u-text-right u-text-mute u-text-small">
                    <span class="c-badge c-badge--primary c-badge--xsmall">Прием заказов</span>
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
                <span class="c-badge c-badge--danger">10.08.2019</span>
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
                asignee: {}

            }
        },
        created() {
            this.taskNumber = document.querySelector("span[task-number]").innerHTML;
            this.loadData();
            this.$http.get('/task/' + this.taskNumber + '/data').then(response => {
                if (response.status === 200) {
                    let task = response.data.task;
                    this.author = task.author;
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
            }
        }
    }
</script>

<style scoped>

</style>
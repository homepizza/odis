<template>
    <article class="c-stage" style="min-height: 355px;">
        <div class="c-stage__header o-media u-justify-start">
            <div class="c-stage__icon o-media__img">
                <i class="fa fa-laptop"></i>
            </div>
            <div class="c-stage__header-title o-media__body">
                <h6 class="u-mb-zero">{{ task.title }}</h6>
                <p class="u-text-xsmall u-text-mute">Была в работе: 05.08.2019 | Ожидается через: 10 дней(-я)</p>
            </div>
        </div>

        <div class="c-stage__panel u-p-medium">

            <p class="u-text-mute u-text-uppercase u-text-small u-mb-xsmall">Описание</p>
            <div class="u-mb-medium u-text-mute u-text-small"
                 style="list-style-type: revert !important;"
                 v-html="task.body"
            >
            </div>

            <p class="u-text-mute u-text-uppercase u-text-small u-mb-xsmall" v-if="attachments.length !== 0">
                Прикрепленные файлы
            </p>
            <div class="row u-mb-medium" v-if="attachments.length !== 0">
                <div class="col-md-6 col-lg-8">
                    <ul>
                        <li class="u-mb-xsmall u-text-small u-color-primary" v-for="attachment in attachments">
                            <i class="fa fa-file-text-o u-text-mute u-mr-xsmall"></i>
                            <a :href="attachment.link">{{ attachment.filename }}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row" style="margin-top: 20px;"></div>
        </div>
    </article>
</template>

<script>
    export default {
        name: "Task",
        data: function () {
            return {
                taskNumber: '',
                task: '',
                attachments: []
            }
        },
        beforeCreate() {
            this.taskNumber = document.querySelector("span[task-number]").innerHTML;
            this.$http.get('/task/' + this.taskNumber + '/data').then(response => {
                if (response.status === 200) {
                    this.task = response.data;
                    this.attachments = response.data.attachments;
                }
            });
        }
    }
</script>

<style>

</style>
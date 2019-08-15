<template>
    <article class="c-stage">
        <div class="c-stage__header o-media u-justify-start">
            <div class="c-stage__icon o-media__img">
                <i class="fa fa-laptop"></i>
            </div>
            <div class="c-stage__header-title o-media__body" style="width: 80%;">
                <h6 class="u-mb-zero" v-if="!$store.state.Task.edit">{{ title }}</h6>
                <input type="text" class="c-input large"
                    v-if="$store.state.Task.edit"
                    @change="setTitle"
                    v-model="title"
                    placeholder="Заголовк задачи"
                    style="width: 100%;"
                >
                <p class="u-text-xsmall u-text-mute" v-show="false">
                    Была в работе: 05.08.2019 | Ожидается через: 10 дней(-я)
                    #1 Была в работе: N дней
                    #2 Попала в работу: 05.08.2019 | Текущий статус: 06.08.2019
                </p>
            </div>
        </div>

        <div class="c-stage__panel u-p-medium">

            <p class="u-text-mute u-text-uppercase u-text-small u-mb-xsmall">Описание</p>
            <div class="u-mb-medium u-text-mute u-text-small"
                 style="list-style-type: revert !important;"
                 v-if="!$store.state.Task.edit"
                 v-html="description"
            >
            </div>
            <div v-if="$store.state.Task.edit">
                <trumbowyg v-model="description" :config="config" @tbw-blur="setDescription"
                           class="form-control"
                           name="description"
                >
                </trumbowyg>
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
    import Trumbowyg from 'vue-trumbowyg';
    import 'trumbowyg/dist/ui/trumbowyg.css';
    export default {
        name: "Task",
        data: function () {
            return {
                config: {},
                taskNumber: '',
                task: '',
                attachments: [],
                title: '',
                description: ''
            }
        },
        beforeCreate() {
            this.taskNumber = document.querySelector("span[task-number]").innerHTML;
            this.$http.get('/task/' + this.taskNumber + '/data').then(response => {
                if (response.status === 200) {
                    this.task = response.data;
                    this.attachments = response.data.attachments;
                    this.title = response.data.title;
                    this.description = response.data.body;
                }
            });
        },
        methods: {
            setTitle: function () {
                this.$store.commit('setTitle', this.title);
            },
            setDescription: function () {
                this.$store.commit('setDescription', this.description);
            }
        },
        components: {
            Trumbowyg
        }
    }
</script>

<style>

</style>
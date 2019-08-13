<template>
    <article class="c-stage">
        <div class="c-stage__header o-media u-justify-start">
            <div class="c-stage__header-title o-media__body" style="width: 80%;">
                <input type="text" class="c-input large"
                       @change="setTitle" v-model="title" placeholder="Заголовк задачи"
                >
            </div>
        </div>

        <div class="c-stage__panel u-p-medium">

            <p class="u-text-mute u-text-uppercase u-text-small u-mb-xsmall">Описание</p>
            <div>
                <trumbowyg v-model="description" :config="config" @tbw-blur="setDescription"
                           class="form-control" name="description"
                >
                </trumbowyg>
            </div>
            <div class="row" style="margin-top: 20px;">
                <div class="col-12">
                    <p class="u-text-mute u-text-uppercase u-mb-medium">Прикрепление файлов</p>

                    <form action="/file-upload" class="dropzone" id="custom-dropzone" style="height: 150px;">
                        <div class="dz-message" data-dz-message>
                            <i class="dz-icon fa fa-cloud-upload"></i>
                            <span>Перетащите файл в окно или загрузите по клику</span>
                        </div>

                        <div class="fallback">
                            <input name="file" type="file" multiple>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row" style="margin-top: 20px;"></div>
        </div>
    </article>
</template>

<script>
    // Import this component
    import Trumbowyg from 'vue-trumbowyg';

    // Import editor css
    import 'trumbowyg/dist/ui/trumbowyg.css';

    export default {
        name: "TaskCreate",
        data: function () {
            return {
                title: null,
                description: null,
                content: null,
                config: {

                }
            }
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
    div.trumbowyg-button-pane {
        z-index: 0;
    }
</style>
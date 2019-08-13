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

<!--                    <form action="/task/attach" class="dropzone" id="custom-dropzone" style="height: 150px;">-->
<!--                        <div class="dz-message" data-dz-message>-->
<!--                            <i class="dz-icon fa fa-cloud-upload"></i>-->
<!--                            <span>Перетащите файл в окно или загрузите по клику</span>-->
<!--                        </div>-->

<!--                        <div class="fallback">-->
<!--                            <input name="file" type="file" multiple>-->
<!--                        </div>-->
<!--                    </form>-->
                    <vue-dropzone
                            class="dropzone"
                            ref="dropzone"
                            id="custom-dropzone"
                            :options="dropzoneOptions"
                            @vdropzone-success="afterCompletedFiles"
                            @vdropzone-removed-file="removedFile"
                    >
                    </vue-dropzone>
                </div>
            </div>
            <div class="row" style="margin-top: 20px;"></div>
        </div>
    </article>
</template>

<script>
    import Trumbowyg from 'vue-trumbowyg';
    import 'trumbowyg/dist/ui/trumbowyg.css';
    import vue2Dropzone from 'vue2-dropzone'

    export default {
        name: "TaskCreate",
        data: function () {
            return {
                title: null,
                description: null,
                files: [],
                config: {},
                dropzoneOptions: {
                    url: '/task/attach',
                    thumbnailWidth: 150,
                    maxFilesize: 30,
                    addRemoveLinks: true,
                    maxFiles: 7
                }
            }
        },
        methods: {
            setTitle: function () {
                this.$store.commit('setTitle', this.title);
            },
            setDescription: function () {
                this.$store.commit('setDescription', this.description);
            },
            afterCompletedFiles: function (file, response) {
                this.files[file.name] = response.link;
                this.$store.commit('setAttachments', this.files)
            },
            removedFile: function (file) {
                let delFile = {link: this.files[file.name]};
                console.log(delFile);
                this.$http.patch('/task/attach', delFile).then(response => {
                    delete this.files[file.name];
                    this.$store.commit('setAttachments', this.files);
                });
            }
        },
        components: {
            Trumbowyg,
            vueDropzone: vue2Dropzone
        }
    }
</script>

<style>
    div.trumbowyg-button-pane {
        z-index: 0;
    }
</style>
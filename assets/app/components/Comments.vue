<template>
    <div>
        <div class="c-stage">
            <div class="c-stage__panel up-medium">
                <p class="u-text-mute u-text-uppercase u-text-small u-mb-xsmall" style="padding: 12px 0 0 30px;">
                    Комментарии:
                </p>
            </div>
            <div class="c-stage__panel up-medium" v-if="comments.length === 0">
                <h4 class="c-board__header u-text-mute u-justify-center">
                    Еще никто не оставлял комментарии...
                </h4>
            </div>

            <!-- Comment -->
            <div class="c-stage__panel u-p-medium" v-for="comment in comments">
                <div class="o-media u-mb-small">
                    <div class="o-media__img u-mr-xsmall">
                        <div class="c-avatar c-avatar--xsmall">
                            <img class="c-avatar__img" src="/media/theme/user5.png" alt="Profile Title">
                        </div>

                    </div>
                    <div class="o-media__body">
                        <h6 class="u-mb-zero u-text-small">{{ comment.user.username }}</h6>
                        <p class="u-text-mute u-text-xsmall">{{ comment.createdAt | formatDate }}</p>
                    </div>
                </div>
                <p class="u-mb-xsmall">{{ comment.comment }}</p>
            </div>

        </div>
        <div class="c-post">
            <textarea class="c-post__content" v-model="userComment"></textarea>

            <div class="c-post__toolbar">
                <button class="c-btn c-btn--success u-float-right" :disabled="userComment.length === 0" @click="publish">
                    Опубликовать
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Comments",
        data: function () {
            return {
                taskNumber: '',
                userComment: '',
                comments: []
            }
        },
        mounted() {
            this.taskNumber = document.querySelector("span[task-number]").innerHTML;
            this.$http.get('/comments/task/' + this.taskNumber).then(response => {
                this.comments = response.data;
            });
        },
        methods: {
            publish: function () {
                let data = {
                    task: this.taskNumber,
                    comment: this.userComment
                };
                this.$http.post('/comments/save', data).then(response => {
                    if (response.status === 200) {
                        let comment = response.data;
                        this.comments.push(comment);
                        this.userComment = '';
                        this.$store.commit('setNewComments', true);
                    }
                });
            }
        }
    }
</script>

<style>

</style>
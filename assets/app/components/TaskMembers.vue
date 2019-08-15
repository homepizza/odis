<template>
    <div class="c-card u-mb-medium">
        <div class="u-p-medium">
            <h5 class="u-h6 u-mb-medium">Участвуют в задаче</h5>

            <div class="o-media u-mb-small" v-for="member in members">
                <p class="u-hidden-visually">{{ needUpdate }}</p>
                <div class="o-media__img u-mr-xsmall">
                    <div class="c-avatar c-avatar--xsmall">
                        <img class="c-avatar__img" src="/media/theme/user5.png" alt="Profile Title">
                    </div>
                </div>
                <div class="o-media__body">
                    <h6 class="u-text-small u-mb-zero">{{ member.fullname }}</h6>
                    <p class="u-text-mute u-text-xsmall">{{ member.usertype }}</p>
                </div>
            </div>

        </div>

        <div class="u-pv-small  u-border-top u-text-center">
            <a class="u-text-mute u-text-uppercase u-text-xsmall">Сообщений: {{ messages }}</a>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TaskMembers",
        data: function () {
            return {
                taskNumber: '',
                members: [],
                messages: 0
            }
        },
        mounted() {
            this.taskNumber = document.querySelector("span[task-number]").innerHTML;
            this.loadMembersInfo();
        },
        computed: {
            needUpdate() {
                this.loadMembersInfo();
                this.$store.commit('setNewComments', false);
                return this.$store.state.Task.comments;
            }
        },
        methods: {
            loadMembersInfo() {
                this.$http.get('/task/' + this.taskNumber + '/members').then(response => {
                    let data = response.data;
                    this.members = data.members;
                    this.messages = data.messages;
                    this.$store.commit('setISAuthor', data.author);
                });
            }
        }
    }
</script>

<style>

</style>
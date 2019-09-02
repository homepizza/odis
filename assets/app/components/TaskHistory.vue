<template>
    <article class="c-stage" id="stages" style="margin-bottom: 10px;">
        <a class="c-stage__header u-flex u-justify-between" data-toggle="collapse" href="#stage-panel2" aria-expanded="false" aria-controls="stage-panel2">
            <h6 class="u-text-mute u-text-uppercase u-text-small u-mb-zero">История задачи</h6>

            <i class="fa fa-angle-down u-text-mute"></i>
        </a>

        <div class="c-stage__panel c-stage__panel--mute collapse" id="stage-panel2">
            <div class="u-p-medium">
                <ul>
                    <li class="u-mb-xsmall u-text-small u-color-primary" v-for="history in actualHistory">
                        <i class="fa fa-calendar u-text-mute u-mr-xsmall"></i>
                        {{ history.dateStatus | formatDate }} &rarr; <i class="fa fa-circle-o" :class="history.status.class"></i>
                        {{ history.status.name }}
                        <p v-if="history.asignee" style="padding-left: 25px;">
                            <em style="font-size: 12px;">{{ history.asignee.username }}</em>
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </article>
</template>

<script>
    export default {
        name: "TaskHistory",
        data: function () {
            return {
                taskNumber: '',
                taskHistory: []
            }
        },
        created() {
            this.taskNumber = document.querySelector("span[task-number]").innerHTML;
            this.loadHistory();
        },
        computed: {
            actualHistory() {
                let needUpdate = this.$store.state.Task.taskHistory;
                if (needUpdate) { this.loadHistory(); }
                setTimeout(function () {}, 1000);
                return this.taskHistory;
            }
        },
        methods: {
            loadHistory: function () {
                this.$http.get('/task/' + this.taskNumber + '/history').then(response => {
                    if (response.status === 200) {
                        this.taskHistory = response.data;
                        this.$store.commit('setTaskHistory', false);
                    }
                });
            }
        }
    }
</script>

<style>

</style>
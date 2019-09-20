<template>
    <div>
        <gantt-elastic
                :options="options"
                :tasks="tasks"
                @tasks-changed="tasksUpdate"
                @options-changed="optionsUpdate"
                @dynamic-style-changed="styleUpdate"
        >
            <gantt-header slot="header" :options="options"></gantt-header>
        </gantt-elastic>
    </div>
</template>

<script>
    import GanttElastic from "gantt-elastic";
    import GanttHeader from "gantt-elastic-header";
    import dayjs from "dayjs";
    // just helper to get current dates
    function getDate(hours) {
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear();
        const currentMonth = currentDate.getMonth();
        const currentDay = currentDate.getDate();
        const timeStamp = new Date(
            currentYear,
            currentMonth,
            currentDay,
            0,
            0,
            0
        ).getTime();
        return new Date(timeStamp + hours * 60 * 60 * 1000).getTime();
    }
    let tasks = [
        {
            id: 1,
            label: "Тестовая задача",
            user: 'Александр Малоземов',
            start: 1568960127000,
            duration: 259200000,
            percent: 100,
            type: "task",
            style: {
                base: {
                    fill: "#0287D0",
                    stroke: "#0077C0"
                }
            }
        }
    ];
    let options = {
        taskMapping: {
            progress: "percent"
        },
        maxRows: 1000,
        maxHeight: 768,
        title: {
            label: "Ориентировочный ход выполнения",
            html: false
        },
        row: {
            height: 24
        },
        calendar: {
            hour: {
                display: true
            }
        },
        chart: {
            progress: {
                bar: false
            },
            expander: {
                display: true
            }
        },
        taskList: {
            expander: {
                straight: true
            },
            columns: [
                {
                    id: 1,
                    label: "№",
                    value: "id",
                    width: 40
                },
                {
                    id: 2,
                    label: "Задача",
                    value: "label",
                    width: 200,
                    expander: true,
                    html: true
                },
                {
                    id: 3,
                    label: "Автор",
                    value: "user",
                    width: 150,
                    html: true
                },
                {
                    id: 3,
                    label: "Начало",
                    value: task => dayjs(task.start).format("YYYY-MM-DD"),
                    width: 78
                },
                {
                    id: 4,
                    label: "Тип",
                    value: "type",
                    width: 68
                },
                {
                    id: 5,
                    label: "%",
                    value: "progress",
                    width: 35,
                    style: {
                        "task-list-header-label": {
                            "text-align": "center",
                            width: "100%"
                        },
                        "task-list-item-value-container": {
                            "text-align": "center",
                            width: "100%"
                        }
                    }
                }
            ]
        },
        locale: {
            'name': "ru",
            'Now': "Сейчас",
            "X-Scale": "Увеличить",
            "Y-Scale": "Растянуть список",
            "Task list width": "Список задач",
            "Before/After": "Показать больше дней",
            "Display task list": "Показать задачи",
            'weekStart': 0,
            'weekdays': 'Воскресенье_Понедельник_Вторник_Среда_Четверг_Пятница_Суббота'.split('_'),
            'weekdaysShort': 'Вс_Пн_Вт_Ср_Чт_Пт_Сб'.split('_'),
            'months': 'Январь_Февраль_Март_Апрель_Май_Июнь_Июль_Август_Сентябрь_Октябрь_Ноябрь_Декабрь'.split('_'),
            'monthsShort': 'янв_фев_март_апр_май_июнь_июль_авг_сент_окт_нояб_дек'.split('_'),
        }
    };
    export default {
        name: "WorkProcess",
        components: {
            'gantt-header': GanttHeader,
            'gantt-elastic': GanttElastic
        },
        data() {
            return {
                tasks,
                options,
                dynamicStyle: {},
                lastId: 22
            };
        },
        mounted() {
            this.loadTasks();
        },
        methods: {
            loadTasks: function() {
                this.$http.get('/work-process/tasks').then(response => {
                    if (response.status === 200) {
                        this.tasksUpdate(response.data);
                        this.optionsUpdate(options);
                    }
                }).catch(reason => {
                    this.$swal({
                        position: 'center',
                        type: 'error',
                        title: 'Не удалось отобразить задачи!',
                        text: reason.message,
                        showConfirmButton: false
                    });
                })
            },
            tasksUpdate(tasks) {
                if (tasks.length >= 1) {
                    this.tasks = tasks;
                }
            },
            optionsUpdate(options) {
                if (this.tasks.length >= 1) {
                    this.options = options;
                }
            },
            styleUpdate(style) {
                this.dynamicStyle = style;
            }
        }
    };
</script>

<style>
    .vue-slider {
        z-index: 0;
    }
</style>
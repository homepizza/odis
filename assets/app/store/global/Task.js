const state = {
    author: {},
    title: null,
    description: null,
    priority: {},
    type: {},
    area: {},
    asignee: {},
    status: {},
    dueDate: null,
    solutionLink: null,
    attachments: new Map(),
    comments: false,
    edit: false,
    isAuthor: false,
    hasWork: false,
    workflow: false,
    taskNumber: null
};

const mutations = {
    setAuthor (state, author) {
        state.author = author
    },
    setTitle (state, title) {
        state.title = title;
    },
    setDescription (state, description) {
        state.description = description;
    },
    setPriority (state, priority) {
        state.priority = priority;
    },
    setType (state, type) {
        state.type = type;
    },
    setArea (state, area) {
        state.area = area;
    },
    setAsignee (state, asignee) {
        state.asignee = asignee;
    },
    setStatus (state, status) {
        state.status = status;
    },
    setDueDate (state, dueDate) {
        state.dueDate = dueDate;
    },
    setSolutionLink (state, solutionLink) {
        state.solutionLink = solutionLink;
    },
    setAttachments (state, attachments) {
        state.attachments = attachments;
    },
    setNewComments (state, flag) {
        state.comments = flag;
    },
    setEdit (state, flag) {
        state.edit = flag;
    },
    setISAuthor (state, flag) {
        state.isAuthor = flag;
    },
    setHasWork (state, flag) {
        state.hasWork = flag;
    },
    setWorkflow (state, flag) {
        state.workflow = flag;
    },
    setTaskNumber (state, taskNumber) {
        state.taskNumber = taskNumber;
    }
};

const getters = {
    getTask: state => {
        let Task = {
            taskNumber: state.taskNumber,
            title: state.title,
            description: state.description,
            priority: state.priority,
            type: state.priority,
            area: state.area,
            asignee: state.asignee,
            status: state.status,
            dueDate: state.dueDate,
            solutionLink: state.solutionLink,
            attachments: [...state.attachments.values()]
        };
        return Task;
    }
};

export default {
    state,
    mutations,
    getters
}
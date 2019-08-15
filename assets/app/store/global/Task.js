const state = {
    title: null,
    description: null,
    priority: null,
    type: null,
    area: null,
    asignee: null,
    status: null,
    dueDate: null,
    solutionLink: null,
    attachments: new Map(),
    comments: false,
    edit: false,
    isAuthor: false,
    hasWork: false
};

const mutations = {
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
    }
};

const getters = {
    getTask: state => {
        let Task = {
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
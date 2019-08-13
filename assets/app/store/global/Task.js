const state = {
    title: null,
    description: null,
    priority: null,
    type: null,
    area: null,
    attachments: new Map()
};

const mutations = {
    setTitle (state, title) {
        state.title = title
    },
    setDescription (state, description) {
        state.description = description
    },
    setPriority (state, priority) {
        state.priority = priority
    },
    setType (state, type) {
        state.type = type
    },
    setArea (state, area) {
        state.area = area
    },
    setAttachments (state, attachments) {
        state.attachments = attachments
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
const state = {
    title: null,
    description: null,
    priority: null,
    type: null,
    area: null
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
    }
};

export default {
    state,
    mutations
}
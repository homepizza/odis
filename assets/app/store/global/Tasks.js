const state = {
    sortNumber: false,
    sortDueDate: false
};

const mutations = {
    setSortNumber (state, flag) {
        state.sortNumber = flag;
    },
    setSortDueDate (state, flag) {
        state.sortDueDate = flag;
    }
};

export default {
    state,
    mutations
}
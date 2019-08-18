const state = {
    applyFilters: false,
    filterAuthor: '',
    filterAsignee: '',
    filterPriority: '',
    filterType: '',
    filterArea: '',
    filterDueFrom: '',
    filterDueTo: ''
};

const mutations = {
    setApplyFilters (state, flag) {
        state.applyFilters = flag;
    },
    setFilterAuthor (state, author) {
        state.filterAuthor = author;
    },
    setFilterAsignee (state, asignee) {
        state.filterAsignee = asignee;
    },
    setFilterPriority (state, priority) {
        state.filterPriority = priority;
    },
    setFilterType (state, type) {
        state.filterType = type;
    },
    setFilterArea (state, area) {
        state.filterArea = area;
    },
    setFilterDueFrom (state, dueFrom) {
        state.filterDueFrom = dueFrom;
    },
    setFilterDueTo (state, dueTo) {
        state.filterDueTo = dueTo;
    }
};

export default {
    state,
    mutations
}
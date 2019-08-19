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

const actions = {
    setFilters(context, data) {
        context.commit('setFilterAuthor', data.author);
        context.commit('setFilterAsignee', data.asignee);
        context.commit('setFilterPriority', data.priority);
        context.commit('setFilterType', data.type);
        context.commit('setFilterArea', data.area);
        context.commit('setFilterDueFrom', data.dueFrom);
        context.commit('setFilterDueTo', data.dueTo);
        context.commit('setApplyFilters', data.apply);
    }
};

const getters = {
    getFilters: state => {
        let Filters = {
            author: state.filterAuthor,
            asignee: state.filterAsignee,
            priority: state.filterPriority,
            type: state.filterType,
            area: state.filterArea,
            dueFrom: state.filterDueFrom,
            dueTo: state.filterDueTo
        };
        return Filters;
    }
};

export default {
    state,
    mutations,
    actions,
    getters
}
const state = {
    query: {
        page: 1,
        h_index: null,
    },
    filters: {
        h_index: [
            '',
            'Q1',
            'Q2',
            'Q3',
            'Q4',
        ],
    }
}

const getters = {
    getQuery: state => {
        return state.query
    },
    getFilters: state => {
        return state.filters
    }
}

const actions = {
    
}

const mutations = {
    UPDATE_QUERY_FILTER: (state, { type, value }) => {
        state.query[type] = value
    },
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}

const state = {
    all: [],
    category: {},
}

const getters = {
    getAll: state => {
        return state.all
    },
}

const actions = {
    async GET_ALL_CATEGORIES({ commit }, query) {
        try {
            let response = await axios.get(`/api/categories`, {
                params: query
            })
            
            commit('SET_ALL_CATEGORIES', {
                all: response.data.data
            })
        } catch(error) {
            console.log(error.response)
        }
    },
}

const mutations = {
    SET_ALL_CATEGORIES: (state, { all }) => {
        state.all = all
    },
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}

const state = {
    all: [],
    queries: {
        sort: '',
        keyword: '',
    },
    user: {
        books: [],
    },
    errors: {},
}

const getters = {
    getUser: state => {
        return state.user
    },
    getAllUsers: state => {
        return state.all
    },
    getQueries: state => {
        return state.queries
    }
}

const actions = {
    async GET_ALL_USERS ({ commit, state }) {
        try {
            let response = await axios.get(`/api/users`, {
                params: state.queries,
            })
            commit('SET_ALL_USERS', {
                users: response.data.data
            })
            return true
        } catch (error) {
            console.log(error.response)
            return false
        }
    },
    async LOAD_USER ({ commit }, id) {
        try {
            let response = await axios.get('/api/users/'+id)
            commit('SET_USER', {
                user: response.data.data
            })
        } catch (error){
            console.log(error)
        }
    },
    async UPDATE_USER ({ commit, state }, { name, email }) {
        try {
            let response = await axios.put('/api/users/'+state.user.id, {
                name: name,
                email: email,
            })

            commit('UPDATE_USER', {
                name: response.data.data.name,
                email: response.data.data.email
            })

            return true
        } catch (error){
            commit('REQUEST_ERROR', error.response.data.errors)
            return false
        }
    },
    async DELETE_USER ({ commit, state }, id) {
        try {
            let response = await axios.delete(`/api/users/${id}`)
            commit('DELETE_USER', id)
            return true
        } catch (error) {
            console.log(error)
            return false
        }
    }
}

const mutations = {
    SET_ALL_USERS: (state, { users }) => {
        state.all = users
    },
    SET_USER: (state, { user }) => {
        state.user = user
    },
    UPDATE_USER: (state, { name, email }) => {
        state.user.name = name
        state.user.email = email
        state.errors = {}
    },
    REQUEST_ERROR: (state, errors) => {
        state.errors = errors
    },
    SET_QUERIES: (state, queries) => {
        for (var name in queries) {
            state.queries[name] = queries[name]
        }
    },
    DELETE_USER: (state, id) => {
        let index = state.all.findIndex(user => user.id === id)
        state.all.splice(index, 1)
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}

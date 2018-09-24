// initial state
const state = {
    book: {
        authors: [],
        spectators: [],
        categories: [],
    },
    errors: [],
    success: [],
    pending: {},
}

const getters = {
    getBook: state => {
        return state.book
    },
    getAuthors: state => {
        return state.book.authors
    },
    getIdentifiers: state => {
        return state.book.identifiers
    },
    getErrors: state => {
        return state.errors
    },
    getPending: state => {
        return state.pending
    }
}

const actions = {
    LOAD_BOOK: function ({ commit }, id) {
      axios.get('/api/books/'+id).then(({ data }) => {
        commit('SET_BOOK', { book: data.data })
      }, (err) => {
        console.log(err)
      })
    },
    ADD_BOOK_USER: function({ commit, state }, { user, type }) {
        axios.post(`/api/books/${state.book.id}/permission`, {
            user_email: user.email,
            type: type,
        })
        .then(({ data }) => {
            commit('PUSH_BOOK_USER', {
                type: type+'s',
                user: user,
            })
        })
        .catch(error => {
            console.log(error)
        })
    },
    DELETE_BOOK_USER: function ({ commit, state }, { id, type }) {
        axios.delete('/api/books/'+state.book.id+'/user/'+id)
        .then(() => {
            commit('DELETE_BOOK_USER', {
                id: id,
                type: type,
            })
        })
        .catch(error => {
            console.log(error)
        })
    },
    UPLOAD_BOOK_COVER: function ({ commit, state }, { cover }) {
        let formData = new FormData();
        formData.append('cover', cover);

        axios.post(`/api/books/${state.book.id}/cover`, 
            formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        ).then(( { data } ) => {
            commit('REQUEST_SUCCESS')
            commit('UPDATE_BOOK_COVER', {
                cover: data.data.cover
            })
        }).catch(error => {
            console.log(error)
            commit('REQUEST_ERROR', {
                errors: error.response.data.errors
            })
        })
    },
    async UPLOAD_BOOK_FILE({ commit, state }, { file }) {
        let formData = new FormData();
        formData.append('file', file);

        try {
            let data = await axios.post(`/api/books/${state.book.id}/file`, 
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            )

            console.log(data)

            commit('UPDATE_BOOK_FILE', {
                file: data.data.data.file,
                download: data.data.data.download
            })

        } catch (error) {
            console.log(error)
        }
    },
    async ADD_BOOK_CATEGORIES({ commit, state }, categories) {
        try {
            let response = await axios.post(`/api/books/${state.book.id}/categories`, categories)
            commit('SET_BOOK_CATEGORIES', {
                categories: response.data.data
            })
            return true
        } catch (error) {
            console.log(error.response)
            return false
        }
    },
    async REMOVE_BOOK_CATEGORY({ commit, state }, { id }) {
        try {
            let response = await axios.delete(`/api/books/${state.book.id}/categories/${id}`)
            commit('REMOVE_BOOK_CATEGORY', {
                id: id
            })
            return true
        } catch(error) {
            console.log(error)
            return false
        }
    }
}

const mutations = {
    SET_BOOK: (state, { book }) => {
        state.book = book
    },
    DELETE_BOOK_USER: (state, { id, type }) => {
        let index = state.book[type].findIndex(user => user.id === id)
        state.book[type].splice(index, 1)
    },
    PUSH_BOOK_USER: (state, { type, user }) => {
        state.book[type].push(user)
    },
    UPDATE_BOOK_COVER: (state, { cover }) => {
        state.book.cover = cover
    },
    UPDATE_BOOK_FILE: (state, { file, download }) => {
        state.book.file = file
        state.book.download = download
    },
    SET_BOOK_CATEGORIES: (state, { categories }) => {
        state.book.categories = categories
    },
    REMOVE_BOOK_CATEGORY: (state, { id }) => {
        let index = state.book.categories.findIndex(category => category.id === id)
        state.book.categories.splice(index, 1)
    },
    REQUEST_SUCCESS: (state) => {
        state.errors = []
    },
    REQUEST_ERROR: (state, { errors }) => {
        state.errors = errors
    },
    REQUEST_PENDING: (state, { data, status }) => {
        state.pending[data] = status
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}

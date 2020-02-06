import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)
axios.defaults.baseURL = 'http://127.0.0.1:8000/api'

export const store = new Vuex.Store({
    state: {
        token: localStorage.getItem('access_token') || null,

    }, 
    getters: { 

    },
    mutations: {
        retriveToken(state, token){
            state.token = token
        }

    },
    actions: {
        retriveToken(context, credentials){
            return new Promise((resolve, reject) => {
                axios.post('/login', {
                    email: credentials.email,
                    password: credentials.password
                }).then(responce => {
                    const token = responce.data.access_token
    
                    localStorage.setItem('access_token', token)
                    
                    context.commit('retriveToken', token)

                    resolve(responce)
                }).catch(error => {
                    console.log(error)
                    reject(error)
                })
            })            
        },

    }
})
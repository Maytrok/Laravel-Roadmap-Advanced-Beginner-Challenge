import Vue from 'vue'
import Vuex from 'vuex'

import axios from "axios";

axios.defaults.headers.common['Accept'] = 'application/json';
Vue.use(Vuex);

export default new Vuex.Store({
    strict: process.env.NODE_ENV !== 'production',
    state: {
        sessionToken: null,
        user: null,
        loading: false,
    },
    mutations: {

        setSessionToken(state, token) {
            state.sessionToken = token;
            localStorage.setItem("sessionToken", token);
            axios.defaults.withCredentials = true;
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        },
        setUser(state, user) {
            state.user = user;
        },
        setLoading(state, loading) {
            state.loading = loading;
        }
    },
    actions: {

        async login({ commit, dispatch }, payload) {

            return new Promise(async (resolve, reject) => {

                try {
                    const response = await axios.post("/api/login", payload);
                    if (response.status == 200) {
                        commit("setSessionToken", response.data);
                        await dispatch("checkAuth")
                        resolve("Login successful");
                    }
                } catch (error) {
                    reject("login failed");
                }
            });
        },
        async initStore({ commit, dispatch }) {
            try {

                commit("setLoading", true);
                commit("setSessionToken", localStorage.getItem("sessionToken"));
                await dispatch("checkAuth");
            } catch (error) {

            }
            commit("setLoading", false);

        },
        async checkAuth({ commit }) {
            return new Promise((resolve, reject) => {

                axios.get("/api/users/checkAuth")
                    .then(res => {
                        if (res.status == 200) {
                            commit("setUser", res.data);
                            resolve("auth successfull");
                        }
                    })
                    .catch(e => {
                        reject("not Authorized");
                    })
            })
        },
    },
});
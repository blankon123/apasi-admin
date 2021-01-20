import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    baseUrl: "/api/v1/publikasi",
    publikasiList: [],
    totalPublikasi: 0
  },
  mutations: {
    INITIALIZE: state => {}
  },
  actions: {
    initialize({ commit, state }) {
      commit("INITIALIZE");
    }
  }
});

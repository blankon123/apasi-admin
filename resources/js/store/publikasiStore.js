import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    table: {
      baseUrl: "/api/v1/publikasi",
      searchPublikasi: "",
      totalPublikasi: 0,
      publikasiList: [],
      pageLength: 0,
      loading: false,
      itemsPerPage: 8,
      headers: [
        {
          text: "Judul Publikasi",
          align: "start",
          value: "judul"
        },
        { text: "Batas Upload", value: "batas_uploadHuman" },
        { text: "Status", value: "stage_id" },
        { text: "", value: "actions" }
      ]
    }
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

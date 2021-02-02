import axios from "axios";

const state = {
  baseUrl: "/api/v1",
  publikasiCount: 0,
  tabelCount: 0
};
const getters = {};
const actions = {
  indexInit({ dispatch }) {
    dispatch("userStore/getCurrentUser", {}, { root: true });
    dispatch("getPublikasiCount");
  },
  getPublikasiCount({ state, dispatch }) {
    if (!state.publikasiCount) {
      axios
        .get(state.baseUrl + "/publikasi/countIndexYear")
        .then(res => {
          state.publikasiCount = res.data;
        })
        .catch(err => {
          console.log(err.message);
        });
    }
  }
};
const mutations = {};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};

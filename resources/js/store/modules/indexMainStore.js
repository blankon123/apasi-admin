import axios from "axios";

const state = {
  baseUrl: "/api/v1",
  publikasiCount: 0,
  tabelDinamisCount: 0,
  notifikasi: {
    data: [],
    loading: true
  }
};
const getters = {};
const actions = {
  indexInit({ dispatch }) {
    dispatch("userStore/getCurrentUser", {}, { root: true });
    dispatch("getPublikasiCount");
    dispatch("getTabelDinamisCount");
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
  },
  getTabelDinamisCount({ state, dispatch }) {
    if (!state.tabelDinamisCount) {
      axios
        .get(state.baseUrl + "/tabelDinamis/countDinamis")
        .then(res => {
          state.tabelDinamisCount = res.data;
        })
        .catch(err => {
          console.log(err.message);
        });
    }
  },
  getNotifikasi({ state, dispatch }) {
    if (!state.notifikasi.length) {
      state.notifikasi.loading = true;
      axios
        .get(state.baseUrl + "/notifikasi/")
        .then(res => {
          state.notifikasi.data = res.data;
          state.notifikasi.loading = false;
        })
        .catch(err => {
          state.notifikasi.loading = false;
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

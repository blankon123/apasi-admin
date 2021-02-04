const state = {
  baseUrl: "/api/v1/publikasi/",
  snackbar: {
    show: false,
    timeout: 3000,
    color: "success",
    text: ""
  },
  publikasiId: null,
  publikasi: {}
};
const getters = {};
const actions = {
  showSnackbar({ state }, { text, type }) {
    state.snackbar.show = true;
    state.snackbar.color = type;
    state.snackbar.text = text;
  },
  setPublikasiDetails({ state }) {
    axios
      .get(state.baseUrl + state.publikasiId)
      .then(res => {
        state.publikasi = res.data[0];
      })
      .catch(err => {
        dispatch("showSnackbar", { text: err.response.data, type: "error" });
      });
  },
  setPublikasiId({ state }, val) {
    state.publikasiId = val;
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

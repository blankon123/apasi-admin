import axios from "axios";

const state = {
  baseUrl: "/api/v1",
  loading: true,
  tabelDinamisTrash: [],
  publikasiTrash: [],
  snackbar: {
    show: false,
    timeout: 3000,
    color: "success",
    text: ""
  }
};
const getters = {};
const actions = {
  showSnackbar({ state }, { text, type }) {
    state.snackbar.show = true;
    state.snackbar.color = type;
    state.snackbar.text = text;
  },

  getTrashAll({ state, dispatch }) {
    state.loading = true;
    dispatch("getTabelDinamisTrash");
    dispatch("getPublikasiTrash");
  },
  getTabelDinamisTrash({ state }) {
    axios
      .get(state.baseUrl + "/tabelDinamis/trash")
      .then(res => {
        state.tabelDinamisTrash = res.data;
        state.loading = false;
      })
      .catch(err => {
        console.log(err.message);
        state.loading = false;
      });
  },
  getPublikasiTrash({ state }) {
    axios
      .get(state.baseUrl + "/publikasi/trash")
      .then(res => {
        state.publikasiTrash = res.data;
        state.loading = false;
      })
      .catch(err => {
        console.log(err.message);
        state.loading = false;
      });
  },
  restore({ state, dispatch }, { type, id }) {
    state.loading = true;
    axios
      .post(state.baseUrl + "/" + type + "/restore/" + id)
      .then(res => {
        state.loading = false;
        dispatch("getTrashAll");
        dispatch("showSnackbar", {
          text: "Sukses Restore",
          type: "success"
        });
      })
      .catch(err => {
        console.log(err.message);
        state.loading = false;
        dispatch("showSnackbar", {
          text: "Ups, Terdapat Kesalahan",
          type: "error"
        });
      });
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

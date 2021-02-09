const state = {
  baseUrl: "/api/v1/publikasi/",
  snackbar: {
    show: false,
    timeout: 3000,
    color: "success",
    text: ""
  },
  error: {
    status: false,
    text: ""
  },
  loading: true,
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
        if (res.data.length != 0) {
          state.publikasi = res.data[0];
          state.loading = false;
        } else {
          state.error.status = true;
          state.error.text = "Ups, Publikasi Terkait Tidak Ditemukan";
        }
      })
      .catch(err => {
        dispatch("showSnackbar", { text: err.response.data, type: "error" });
      });
  },
  setPublikasiId({ state }, val) {
    state.publikasiId = val;
  },
  sendSPRP({ state, dispatch }, pub) {
    state.loading = true;
    axios
      .put(state.baseUrl + "/sprp/" + pub.id, {
        ukuran: pub.ukuran,
        bahasa: pub.bahasa,
        orientasi: pub.orientasi,
        diterbitkan_untuk: pub.diterbitkan_untuk,
        numbering: pub.numbering,
        cover_oleh: pub.cover_oleh,
        abstraksi: pub.abstraksi
      })
      .then(res => {
        if (res.data.length != 0) {
          state.loading = false;
        } else {
          state.error.status = true;
          state.error.text = "Ups, Terdapat Kesalahan";
        }
      })
      .catch(err => {
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

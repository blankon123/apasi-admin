import axios from "axios";

const state = {
  baseUrl: "/api/v1/notifikasi",
  notifikasi: {
    data: [],
    tabel: [],
    publikasi: [],
    loading: true
  }
};
const getters = {};
const actions = {
  getNotifikasiAll({ state, dispatch }) {
    if (!state.notifikasi.length) {
      state.notifikasi.loading = true;
      axios
        .get(state.baseUrl + "/all")
        .then(res => {
          state.notifikasi.data = res.data;
          state.notifikasi.tabel = res.data.filter(obj => {
            return obj.type == "App\\Notifications\\TabelDinamisNotif";
          });
          state.notifikasi.publikasi = res.data.filter(obj => {
            return obj.type == "App\\Notifications\\PublikasiNotif";
          });
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

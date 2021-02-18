const state = {
  baseUrl: "/api/v1/petugas",
  snackbar: {
    show: false,
    timeout: 3000,
    color: "success",
    text: ""
  },
  petugasTable: {
    loading: true,
    headers: [
      {
        text: "Nama",
        align: "start",
        sortable: false,
        value: "nama"
      },
      {
        text: "Tampilan",
        align: "start",
        sortable: false,
        value: "nama_singkat"
      },
      {
        text: "",
        align: "start",
        sortable: false,
        value: "actions"
      }
    ]
  },
  all: []
};
const getters = {};
const actions = {
  showSnackbar({ state }, { text, type }) {
    state.snackbar.show = true;
    state.snackbar.color = type;
    state.snackbar.text = text;
  },
  getPetugasTable({ commit, state, dispatch }) {
    axios
      .get(state.baseUrl + "/all")
      .then(res => {
        commit("changePetugases", res.data);
        commit("changeTableLoading", false);
      })
      .catch(err => {
        dispatch("showSnackbar", {
          text: "Ups, Terdapat Kesalahan",
          type: "error"
        });
        console.log(err.message);
      });
  },
  addPetugas({ state, dispatch }, data) {
    state.petugasTable.loading = true;
    axios
      .post(state.baseUrl + "/", {
        nama: data.nama,
        nama_singkat: data.nama_singkat
      })
      .then(res => {
        dispatch("getPetugasTable");
        dispatch("showSnackbar", {
          text: "Sukses Input Petugas",
          type: "success"
        });
      })
      .catch(err => {
        dispatch("showSnackbar", {
          text: "Ups, Terdapat Kesalahan",
          type: "error"
        });
        console.log(err.message);
      });
  },
  editPetugas({ state, dispatch }, data) {
    state.petugasTable.loading = true;
    axios
      .put(state.baseUrl + "/", {
        id: data.id,
        nama: data.nama,
        nama_singkat: data.nama_singkat
      })
      .then(res => {
        dispatch("getPetugasTable");
        dispatch("showSnackbar", {
          text: "Sukses Edit Petugas",
          type: "success"
        });
      })
      .catch(err => {
        dispatch("showSnackbar", {
          text: "Ups, Terdapat Kesalahan",
          type: "error"
        });
        console.log(err.message);
      });
  },
  deletePetugas({ state, dispatch }, data) {
    state.petugasTable.loading = true;
    axios
      .delete(state.baseUrl + "/", {
        data: { id: data.id }
      })
      .then(res => {
        dispatch("getPetugasTable");
        dispatch("showSnackbar", {
          text: "Sukses Hapus Petugas",
          type: "success"
        });
      })
      .catch(err => {
        dispatch("showSnackbar", {
          text: "Ups, Terdapat Kesalahan",
          type: "error"
        });
        console.log(err.message);
      });
  }
};
const mutations = {
  changePetugases(state, val) {
    state.all = val;
  },
  changeTableLoading(state, val) {
    state.petugasTable.loading = val;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};

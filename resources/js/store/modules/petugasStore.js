const state = {
  baseUrl: "/api/v1/petugas",
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
  getPetugasTable({ commit, state }) {
    axios
      .get(state.baseUrl + "/all")
      .then(res => {
        commit("changePetugases", res.data);
        commit("changeTableLoading", false);
      })
      .catch(err => {
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
      })
      .catch(err => {
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
      })
      .catch(err => {
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
      })
      .catch(err => {
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

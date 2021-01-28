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
  deletePetugas({ state, dispatch }, id) {
    state.petugasTable.loading = true;
    axios
      .delete(state.baseUrl + "/", {
        data: { id: id }
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

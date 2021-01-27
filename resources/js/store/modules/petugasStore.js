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

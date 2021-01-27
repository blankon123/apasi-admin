const state = {
  table: {
    baseUrl: "/api/v1/publikasi",
    searchPublikasi: "",
    searchKey: "",
    totalPublikasi: 0,
    publikasiList: [],
    pageLength: 0,
    loading: false,
    itemsPerPage: 8,
    headers: [
      {
        text: "Judul Publikasi",
        align: "start",
        value: "judul"
      },
      { text: "Batas Upload", value: "batas_uploadHuman" },
      { text: "Status", value: "stage_id" },
      { text: "", value: "actions" }
    ]
  }
};
const getters = {};
const actions = {
  setLoading({ commit }, val) {
    commit("changeLoading", val);
  },
  setTableData({ commit }, res) {
    commit("changeTableData", res);
  },
  refreshTable({ commit }) {
    commit("refreshTable");
  },
  setSearch({ commit }, key) {
    commit("searchTable", key);
  }
};
const mutations = {
  changeLoading(state, val) {
    state.table.loading = val;
  },
  changeTableData(state, res) {
    state.table.publikasiList = res.data.data;
    state.table.pageLength = res.data.last_page;
    state.table.loading = false;
  },
  refreshTable(state, res) {
    state.table.baseUrl = "/api/v1/publikasi";
  },
  searchTable(state, key) {
    state.table.baseUrl = key;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};

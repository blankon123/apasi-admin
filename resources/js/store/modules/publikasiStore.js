const state = {
  baseUrl: "",
  publikasiTable: {
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
      { text: "", value: "actions" }
    ]
  },
  keySearchPublikasi: "",
  currentPage: 1,
  importDialog: {
    show: false,
    loading: false,
    errorStatus: false,
    errorText: "",
    file: null
  },
  dialog: {
    show: false,
    loading: false,
    errorStatus: false,
    errorText: "",
    publikasi: {
      judul_publikasi: "",
      arc: 0,
      tanggal_arc: "",
      bidang: 0
    }
  },
  deleteDialog: {
    show: false,
    targetUrl: "publikasiStore/deletePublikasi",
    form: {
      id: null,
      name: ""
    }
  },
  snackbar: {
    show: false,
    timeout: 3000,
    color: "success",
    text: ""
  },
  year: null
};
const getters = {};
const actions = {
  setLoading({ commit }, val) {
    commit("changeLoading", val);
  },
  setTableData({ state }, requestedPage = 1) {
    state.publikasiTable.loading = true;
    axios
      .get(state.baseUrl, {
        params: {
          page: requestedPage,
          total: state.publikasiTable.itemsPerPage
        }
      })
      .then(res => {
        state.publikasiTable.publikasiList = res.data.data;
        state.publikasiTable.pageLength = res.data.last_page;
        state.currentPage = requestedPage;
        state.publikasiTable.loading = false;
      })
      .catch(err => {
        dispatch("showSnackbar", { text: err.response.data, type: "error" });
      });
  },
  showSnackbar({ state }, { text, type }) {
    state.snackbar.show = true;
    state.snackbar.color = type;
    state.snackbar.text = text;
  },
  refreshTable({ state, dispatch }) {
    state.baseUrl =
      state.year == null ? "/api/v1/publikasi" : "/api/v1/publikasi/year";
    dispatch("setTableData", 1);
  },
  importPublikasi({ state, dispatch }) {
    let formData = new FormData();
    formData.append("file", state.importDialog.file);
    state.importDialog.loading = true;
    axios
      .post("/api/v1/publikasi/import", formData, {
        headers: {
          "Content-Type": "multipart/form-data"
        }
      })
      .then(() => {
        state.importDialog.loading = false;
        state.importDialog.show = false;
        dispatch("refreshTable");
        dispatch("showSnackbar", {
          text: "Sukses Import List",
          type: "success"
        });
        dispatch("indexMainStore/indexInit", {}, { root: true });
      })
      .catch(err => {
        state.importDialog.loading = false;
        state.importDialog.errorStatus = true;
        state.importDialog.errorText = err.response.data;
      });
  },
  deleteDialogShow({ state }, item) {
    state.deleteDialog = {
      show: true,
      targetUrl: "publikasiStore/deletePublikasi",
      form: {
        id: item.id,
        name: item.judul_publikasi
      }
    };
  },
  deleteDialogInit({ state }) {
    state.deleteDialog = {
      show: false,
      targetUrl: "publikasiStore/deletePublikasi",
      form: {
        id: "",
        name: ""
      }
    };
  },
  dialogInit({ state }) {
    state.dialog = {
      show: false,
      loading: false,
      errorStatus: false,
      errorText: "",
      publikasi: {
        judul_publikasi: "",
        arc: 0,
        tanggal_arc: "",
        bidang: 0
      }
    };
  },
  deletePublikasi({ state, commit, dispatch }, form) {
    axios
      .delete("/api/v1/publikasi/", {
        data: { id: state.deleteDialog.form.id }
      })
      .then(res => {
        dispatch("setTableData", 1);
        state.deleteDialog.show = false;
        dispatch("showSnackbar", {
          text: "Sukses Hapus Publikasi",
          type: "success"
        });
      })
      .catch(err => {
        dispatch("showSnackbar", { text: err.response.data, type: "error" });
      });
  },
  setSearch({ state, dispatch }, keyword = "") {
    state.baseUrl =
      state.year == null
        ? "/api/v1/publikasi/search?key=" + keyword
        : "/api/v1/publikasi/searchYear?key=" + keyword;
    state.keySearchPublikasi = keyword;
    dispatch("setTableData", 1);
  },
  addPublikasi({ state, dispatch }, form) {
    state.publikasiTable.loading = true;
    axios
      .post(state.baseUrl + "/", {
        judul_publikasi: form.judul_publikasi,
        jenis_arc: form.arc,
        arc: form.tanggal_arc,
        user_id: form.bidang
      })
      .then(res => {
        dispatch("setTableData");
        dispatch("showSnackbar", { text: res.data, type: "success" });
      })
      .catch(err => {
        dispatch("showSnackbar", {
          text: "Ups, Terjadi Kesalahan",
          type: "error"
        });
        console.log(err.message);
      });
  },
  editPublikasi({ state, dispatch }, form) {
    state.publikasiTable.loading = true;
    axios
      .put(state.baseUrl + "/", {
        id: form.id,
        judul_publikasi: form.judul_publikasi,
        jenis_arc: form.arc,
        arc: form.tanggal_arc,
        user_id: form.bidang
      })
      .then(res => {
        dispatch("setTableData");
        dispatch("showSnackbar", { text: res.data, type: "success" });
      })
      .catch(err => {
        dispatch("showSnackbar", {
          text: "Ups, Terjadi Kesalahan",
          type: "error"
        });
        console.log(err.message);
      });
  },
  setYear({ state }, year) {
    state.year = year;
    state.baseUrl = "/api/v1/publikasi/year";
  },
  setYearNull({ state }) {
    state.year = null;
    state.baseUrl = "/api/v1/publikasi";
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

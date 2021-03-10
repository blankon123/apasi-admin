const bpsApiAxios = require("axios").create();
const state = {
  baseUrl: "/api/v1/tabelDinamis/",
  bpsApiUrl: "https://webapi.bps.go.id/v1/api/",
  tabelTable: {
    loading: false,
    search: "",
    data: [],
    itemsPerPage: 10,
    subject_id: "",
    category_id: "",
    bidang_id: "",
    pageLength: 0,
    headers: [
      {
        align: "start",
        value: "judul",
        width: "70%"
      },
      { value: "actions", align: "end", width: "30%" }
    ]
  },
  keySearchTabel: "",
  snackbar: {
    show: false,
    timeout: 3000,
    color: "success",
    text: ""
  },
  syncStatus: {
    loading: false,
    progress: 0
  },
  subjects: [],
  categories: []
};
const getters = {};
const actions = {
  showSnackbar({ state }, { text, type }) {
    state.snackbar.show = true;
    state.snackbar.color = type;
    state.snackbar.text = text;
  },

  setTableData({ state }, requestedPage = 1) {
    state.tabelTable.loading = true;
    axios
      .get(state.baseUrl, {
        params: {
          page: requestedPage,
          total: state.tabelTable.itemsPerPage,
          key: state.tabelTable.search,
          subject_id: state.tabelTable.subject_id,
          category_id: state.tabelTable.category_id,
          bidang_id: state.tabelTable.bidang_id
        }
      })
      .then(res => {
        state.tabelTable.data = res.data.data;
        state.tabelTable.pageLength = res.data.last_page;
        state.tabelTable.loading = false;
      })
      .catch(err => {
        console.log(err.response.data);
        state.tabelTable.loading = false;
        dispatch("showSnackbar", {
          text: "Ups, Terdapat Kesalahan",
          type: "error"
        });
      });
  },
  setCategories({ state }) {
    if (state.categories.length != 0) {
      return true;
    }
    state.tabelTable.loading = true;
    let temp = [];
    function requestCategories(halaman, afterFunction) {
      bpsApiAxios
        .get(state.bpsApiUrl + "list/", {
          params: {
            model: "subcat",
            domain: "6200",
            key: process.env.MIX_WEBAPI_BPS_KEY,
            page: halaman
          }
        })
        .then(res => {
          afterFunction(res);
        })
        .catch(err => {
          console.log(err.response.data);
          state.syncStatus.loading = false;
          dispatch("showSnackbar", {
            text: "Ups, Terdapat Kesalahan",
            type: "error"
          });
        });
    }
    requestCategories(1, function(res) {
      for (let i = 1; i <= res.data.data[0].pages; i++) {
        requestCategories(i, function(res) {
          temp = [...temp, ...res.data.data[1]];
          if (temp.length == res.data.data[0].total) {
            state.categories = temp;
            state.tabelTable.categories = temp;
          }
        });
      }
    });
  },
  setSubjects({ state }) {
    if (state.subjects.length != 0) {
      return true;
    }
    state.tabelTable.loading = true;
    let temp = [];
    function requestsubjects(halaman, afterFunction) {
      bpsApiAxios
        .get(state.bpsApiUrl + "list/", {
          params: {
            model: "subject",
            domain: "6200",
            key: process.env.MIX_WEBAPI_BPS_KEY,
            page: halaman
          }
        })
        .then(res => {
          afterFunction(res);
        })
        .catch(err => {
          console.log(err.response.data);
          state.syncStatus.loading = false;
          dispatch("showSnackbar", {
            text: "Ups, Terdapat Kesalahan",
            type: "error"
          });
        });
    }
    requestsubjects(1, function(res) {
      for (let i = 1; i <= res.data.data[0].pages; i++) {
        requestsubjects(i, function(res) {
          temp = [...temp, ...res.data.data[1]];
          if (temp.length == res.data.data[0].total) {
            state.subjects = temp;
            state.tabelTable.subjects = temp;
          }
        });
      }
    });
  },
  refreshTable({ dispatch }) {
    dispatch("setTableData", 1);
  },
  syncTabelData({ state, dispatch }) {
    state.syncStatus.loading = true;
    let tempTabel = [];
    function syncDatabase(webapi_tabel) {
      webapi_tabel = webapi_tabel.map(item => {
        return {
          tabel_web_id: item.var_id,
          judul_tabel: item.title,
          subject_id: item.sub_id,
          category_id: state.subjects.find(obj => {
            return obj.sub_id === item.sub_id;
          }).subcat_id
        };
      });
      axios
        .post(state.baseUrl + "sync/", {
          data: webapi_tabel
        })
        .then(res => {
          state.tabelTable.data = res.data.tabel.data;
          state.tabelTable.loading = false;
          state.syncStatus.loading = false;
          state.tabelTable.search = "";
          state.tabelTable.subject_id = "";
          state.tabelTable.category_id = "";
          state.tabelTable.pageLength = res.data.tabel.last_page;
          dispatch("showSnackbar", { text: res.data.msg, type: "success" });
        })
        .catch(err => {
          console.log(err.response.data);
          state.syncStatus.loading = false;
          dispatch("showSnackbar", {
            text: "Ups, Terdapat Kesalahan",
            type: "error"
          });
        });
    }
    function requestTabels(halaman, afterFunction) {
      bpsApiAxios
        .get(state.bpsApiUrl + "list/", {
          params: {
            model: "var",
            domain: "6200",
            key: process.env.MIX_WEBAPI_BPS_KEY,
            page: halaman
          }
        })
        .then(res => {
          afterFunction(res);
        })
        .catch(err => {
          console.log(err.response.data);
          state.syncStatus.loading = false;
          dispatch("showSnackbar", {
            text: "Ups, Terdapat Kesalahan",
            type: "error"
          });
        });
    }
    requestTabels(1, function(res) {
      tempTabel = [...tempTabel, ...res.data.data[1]];
      for (let i = 2; i <= res.data.data[0].pages; i++) {
        requestTabels(i, function(res) {
          tempTabel = [...tempTabel, ...res.data.data[1]];
          state.syncStatus.progress =
            (tempTabel.length * 100) / res.data.data[0].total;
          if (Math.ceil(state.syncStatus.progress) == 100) {
            syncDatabase(tempTabel);
          }
        });
      }
    });
  },

  setSubjectFilter({ state, dispatch }, val) {
    state.tabelTable.subject_id = val;
    dispatch("setTableData");
  },
  setCategoryFilter({ state, dispatch }, val) {
    state.tabelTable.category_id = val;
    state.tabelTable.subject_id = null;
    state.tabelTable.subjects = state.subjects.filter(obj => {
      return obj.subcat_id === val;
    });
    dispatch("setTableData");
  },
  setBidangFilter({ state, dispatch }, val) {
    state.tabelTable.bidang_id = val;
    dispatch("setTableData");
  },

  addTabel({ state, dispatch }, form) {
    state.tabelTable.loading = true;
    axios
      .post(state.baseUrl, {
        judul_tabel: form.judul_tabel,
        subject_id: form.subject_id,
        category_id: form.category_id,
        user_id: form.user_id
      })
      .then(res => {
        dispatch("setTableData");
        dispatch("showSnackbar", { text: res.data, type: "success" });
      })
      .catch(err => {
        console.log(err.message);
        state.tabelTable.loading = false;
        dispatch("showSnackbar", {
          text: "Ups, Terjadi Kesalahan",
          type: "error"
        });
      });
  },
  editTabel({ state, dispatch }, form) {
    state.tabelTable.loading = true;
    axios
      .put(state.baseUrl + form.id, {
        judul_tabel: form.judul_tabel,
        subject_id: form.subject_id,
        category_id: form.category_id,
        user_id: form.user_id
      })
      .then(res => {
        dispatch("setTableData", 1);
        dispatch("showSnackbar", { text: res.data, type: "success" });
      })
      .catch(err => {
        state.tabelTable.loading = false;
        dispatch("showSnackbar", {
          text: "Ups, Terjadi Kesalahan",
          type: "error"
        });
        console.log(err.message);
      });
  },
  deleteTabel({ state, dispatch }, form) {
    state.tabelTable.loading = true;
    axios
      .put(state.baseUrl + "requestDelete", {
        id: form.id
      })
      .then(res => {
        dispatch("setTableData", 1);
        console.log(res.data);
        dispatch("showSnackbar", {
          text: res.data,
          type: "success"
        });
      })
      .catch(err => {
        state.tabelTable.loading = false;
        dispatch("showSnackbar", {
          text: "Ups, Terjadi Kesalahan",
          type: "error"
        });
        console.log(err.message);
      });
  },
  editTabelUser({ state, dispatch }, form) {
    state.tabelTable.loading = true;
    axios
      .put(state.baseUrl + form.id, {
        user_id: form.user_id
      })
      .then(res => {
        state.tabelTable.loading = false;
        dispatch("showSnackbar", {
          text: "Sukses Merubah User",
          type: "success"
        });
      })
      .catch(err => {
        state.tabelTable.loading = false;
        dispatch("showSnackbar", {
          text: "Ups, Terjadi Kesalahan",
          type: "error"
        });
        console.log(err.message);
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

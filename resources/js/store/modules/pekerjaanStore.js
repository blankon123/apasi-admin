const state = {
  baseUrl: "/api/v1/pekerjaan",
  snackbar: {
    show: false,
    timeout: 3000,
    color: "success",
    text: ""
  },
  deleteDialog: {
    form: {
      nama: null,
      id: null
    },
    show: false,
    loading: false
  },
  kerjakanDialog: {
    form: {
      nama: null,
      id: null
    },
    show: false,
    loading: false,
    petugas_id: null
  },
  batalDialog: {
    form: {
      nama: null,
      id: null
    },
    show: false,
    loading: false,
    petugas_id: null
  },
  pekerjaan: {
    all: null,
    belum: null,
    sedang: null,
    sudah: null
  },
  isLoading: true,
  petugas: []
};
const getters = {};
const actions = {
  showSnackbar({ state }, { text, type }) {
    state.snackbar.show = true;
    state.snackbar.color = type;
    state.snackbar.text = text;
  },
  init({ state, dispatch }) {
    state.isLoading = true;
    axios
      .get(state.baseUrl + "/")
      .then(res => {
        state.pekerjaan.all = res.data;
        state.pekerjaan.belum = res.data.filter(item => {
          if (item.status == 0) return true;
        });
        state.pekerjaan.sedang = res.data.filter(item => {
          if (item.status == 1) return true;
        });
        state.pekerjaan.sudah = res.data.filter(item => {
          if (item.status == 2) return true;
        });
        state.isLoading = false;
      })
      .catch(err => {
        console.log(err);
        dispatch("showSnackbar", { text: err.response.data, type: "error" });
        state.isLoading = false;
      });
  },

  deleteDialogInit({ state }) {
    state.deleteDialog = {
      form: {
        nama: null,
        id: null
      },
      show: false,
      loading: false
    };
  },
  deleteDialogShow({ state }, item) {
    state.deleteDialog = {
      form: {
        nama: item.nama,
        id: item.id
      },
      show: true,
      loading: false
    };
  },
  hapus({ state, dispatch }, id) {
    axios
      .delete(state.baseUrl + "/", {
        data: { id: id }
      })
      .then(res => {
        dispatch("init");
        state.deleteDialog.show = false;
        dispatch("showSnackbar", {
          text: "Sukses Hapus Pekerjaan",
          type: "success"
        });
      })
      .catch(err => {
        state.deleteDialog.loading = false;
        state.deleteDialog.show = false;
        dispatch("showSnackbar", {
          text: "Ups,Terdapat Kesalahan",
          type: "error"
        });
        console.log(err.response.data);
      });
  },

  kerjakanDialogInit({ state }) {
    state.kerjakanDialog = {
      form: {
        nama: null,
        id: null
      },
      show: false,
      loading: false,
      petugas_id: null
    };
  },
  kerjakanDialogShow({ state }, item) {
    state.kerjakanDialog = {
      form: {
        nama: item.nama,
        id: item.id
      },
      show: true,
      loading: false,
      petugas_id: null
    };
  },
  kerjakan({ state, dispatch }) {
    axios
      .put(state.baseUrl + "/kerjakan/" + state.kerjakanDialog.form.id, {
        petugas_id: state.kerjakanDialog.form.petugas_id
      })
      .then(res => {
        dispatch("init");
        state.kerjakanDialog.show = false;
        dispatch("showSnackbar", {
          text: "Sukses Konfirmasi Pekerjaan",
          type: "success"
        });
      })
      .catch(err => {
        state.kerjakanDialog.loading = false;
        state.kerjakanDialog.show = false;
        dispatch("showSnackbar", {
          text: "Ups,Terdapat Kesalahan",
          type: "error"
        });
        console.log(err.response.data);
      });
  },

  batalDialogInit({ state }) {
    state.batalDialog = {
      form: {
        nama: null,
        id: null
      },
      show: false,
      loading: false,
      petugas_id: null
    };
  },
  batalDialogShow({ state }, item) {
    state.batalDialog = {
      form: {
        nama: item.nama,
        id: item.id
      },
      show: true,
      loading: false,
      petugas_id: null
    };
  },
  batal({ state, dispatch }) {
    axios
      .put(state.baseUrl + "/batal/" + state.batalDialog.form.id, {
        petugas_id: state.batalDialog.form.petugas_id
      })
      .then(res => {
        dispatch("init");
        state.batalDialog.show = false;
        dispatch("showSnackbar", {
          text: "Sukses Konfirmasi Pekerjaan",
          type: "success"
        });
      })
      .catch(err => {
        state.batalDialog.loading = false;
        state.batalDialog.show = false;
        dispatch("showSnackbar", {
          text: "Ups,Terdapat Kesalahan",
          type: "error"
        });
        console.log(err.response.data);
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

import axios from "axios";

const state = {
  baseUrl: "api/v1/user",
  user: {},
  users: [],
  userTable: {
    header: [
      {
        text: "Bidang",
        align: "start",
        sortable: false,
        value: "nama_bidang"
      },
      {
        text: "Deskripsi",
        align: "start",
        sortable: false,
        value: "name"
      },
      {
        text: "",
        align: "start",
        sortable: false,
        value: "actions"
      }
    ],
    loading: true,
    items: []
  }
};
const getters = {};
const actions = {
  setUser({ commit }, user) {
    commit("changeUser", user);
  },
  getUserTable({ commit, state }) {
    state.userTable.loading = true;
    axios
      .get(state.baseUrl + "/all")
      .then(res => {
        commit("changeUserTable", res.data);
        state.userTable.loading = false;
      })
      .catch(err => {
        console.log(err.message);
      });
  },
  getBidang({ commit, state }) {
    axios
      .get(state.baseUrl + "/bidang")
      .then(res => {
        commit("changeUserBidang", res.data);
      })
      .catch(err => {
        console.log(err.message);
      });
  },
  addUser({ state, dispatch }, data) {
    state.userTable.loading = true;
    axios
      .post(state.baseUrl + "/", {
        username: data.username,
        nama_bidang: data.nama_bidang,
        name: data.name,
        password: data.password
      })
      .then(res => {
        dispatch("getUserTable");
      })
      .catch(err => {
        console.log(err.message);
      });
  },
  editUser({ state, dispatch }, form) {
    state.userTable.loading = true;
    axios
      .put(state.baseUrl + "/", {
        id: form.id,
        username: form.username,
        nama_bidang: form.nama_bidang,
        name: form.name
      })
      .then(res => {
        dispatch("getUserTable");
      })
      .catch(err => {
        console.log(err.message);
      });
  },
  resetUserPassword({ state, dispatch }, form) {
    state.userTable.loading = true;
    console.log(form.password);
    axios
      .put(state.baseUrl + "/password", {
        id: form.id,
        password: form.password
      })
      .then(res => {
        dispatch("getUserTable");
      })
      .catch(err => {
        console.log(err.message);
      });
  },
  deleteUser({ state, dispatch }, data) {
    state.userTable.loading = true;
    axios
      .delete(state.baseUrl + "/", {
        data: { id: data.id }
      })
      .then(res => {
        dispatch("getUserTable");
      })
      .catch(err => {
        console.log(err.message);
      });
  },
  getCurrentUser({ state, dispatch }) {
    if (!Object.keys(state.user).length) {
      axios
        .get("/api/v1/user/")
        .then(response => {
          dispatch("setUser", response.data);
        })
        .catch(errors => {
          console.log(errors);
        });
    }
  }
};
const mutations = {
  changeUser(state, user) {
    state.user = user;
  },
  changeUserTable(state, users) {
    state.userTable.items = users;
    state.userTable.loading = false;
  },
  changeUserBidang(state, bidangs) {
    state.users = bidangs;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};

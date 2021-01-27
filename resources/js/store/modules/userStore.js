import axios from "axios";

const state = {
  baseURL: "api/v1/user",
  user: {},
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
        text: "Akun",
        sortable: false,
        value: "username"
      }
    ],
    loading: true,
    items: []
  }
};
const getters = {
  isSavedUser: state => {
    return !!Object.keys(state.user).length;
  }
};
const actions = {
  setUser({ commit }, user) {
    commit("changeUser", user);
  },
  getUserTable({ commit, state }) {
    axios
      .get(state.baseURL + "/all")
      .then(res => {
        commit("changeUserTable", res.data);
      })
      .catch(err => {
        console.log(err.message);
      });
  }
};
const mutations = {
  changeUser(state, user) {
    state.user = user;
  },
  changeUserTable(state, users) {
    state.userTable.items = users;
    state.userTable.loading = false;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};

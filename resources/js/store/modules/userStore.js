const state = {
  user: {}
};
const getters = {
  isSavedUser: state => {
    return !!Object.keys(state.user).length;
  }
};
const actions = {
  setUser({ commit }, user) {
    commit("changeUser", user);
  }
};
const mutations = {
  changeUser(state, user) {
    state.user = user;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};

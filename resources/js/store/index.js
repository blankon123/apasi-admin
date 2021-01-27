import Vue from "vue";
import Vuex from "vuex";

import userStore from "./modules/userStore.js";
import publikasiStore from "./modules/publikasiStore.js";
import pekerjaanStore from "./modules/pekerjaanStore.js";
import petugasStore from "./modules/petugasStore.js";

Vue.use(Vuex);
export default new Vuex.Store({
  modules: {
    userStore,
    publikasiStore,
    petugasStore,
    pekerjaanStore
  }
});

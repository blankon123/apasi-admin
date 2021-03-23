import Vue from "vue";
import Vuex from "vuex";

import userStore from "./modules/userStore.js";
import logStore from "./modules/logStore.js";
import trashStore from "./modules/trashStore.js";
import publikasiStore from "./modules/publikasiStore.js";
import publikasiViewStore from "./modules/publikasiViewStore.js";
import pekerjaanStore from "./modules/pekerjaanStore.js";
import petugasStore from "./modules/petugasStore.js";
import tabelDinamisStore from "./modules/tabelDinamisStore.js";
import indexMainStore from "./modules/indexMainStore.js";

Vue.use(Vuex);
export default new Vuex.Store({
  modules: {
    userStore,
    publikasiStore,
    petugasStore,
    pekerjaanStore,
    indexMainStore,
    publikasiViewStore,
    trashStore,
    logStore,
    tabelDinamisStore
  }
});

import Vue from "vue";
import store from "./../store/index.js";
import VueRouter from "vue-router";
import IndexMain from "./../components/views/main/IndexMain.vue";
import AccountData from "./../components/views/main/AccountData.vue";
import Dashboard from "./../components/views/main/Dashboard.vue";
import Publikasi from "./../components/views/main/Publikasi.vue";
import PublikasiAll from "./../components/views/main/PublikasiAll.vue";
import PublikasiView from "./../components/views/main/PublikasiView.vue";
import TabelStatis from "./../components/views/main/TabelStatis.vue";
import TabelDinamis from "./../components/views/main/TabelDinamis.vue";
import TabelView from "./../components/views/main/TabelView.vue";
import Pekerjaan from "./../components/views/main/Pekerjaan.vue";
import Login from "./../components/views/auth/Login.vue";
import NotFound from "./../components/views/error/NotFound.vue";

Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    name: "Main",
    component: IndexMain,
    meta: { requiresAuth: true },
    children: [
      {
        path: "/account",
        name: "Account",
        meta: { requiresAuth: true },
        components: {
          default: IndexMain,
          MainView: AccountData
        }
      },
      {
        path: "/dashboard",
        name: "Dashboard",
        meta: { requiresAuth: true },
        components: {
          default: IndexMain,
          MainView: Dashboard
        }
      },
      {
        path: "/publikasi",
        name: "Publikasi",
        meta: { requiresAuth: true },
        components: {
          default: IndexMain,
          MainView: Publikasi
        }
      },
      {
        path: "/tabel",
        name: "TabelStatis",
        meta: { requiresAuth: true },
        components: {
          default: IndexMain,
          MainView: TabelStatis
        }
      },
      {
        path: "/publikasiAll",
        name: "PublikasiAll",
        meta: { requiresAuth: true },
        components: {
          default: IndexMain,
          MainView: PublikasiAll
        }
      },
      {
        path: "/tabelDinamis",
        name: "TabelDinamis",
        meta: { requiresAuth: true },
        components: {
          default: IndexMain,
          MainView: TabelDinamis
        }
      },
      {
        path: "/publikasi/:id",
        name: "PublikasiView",
        meta: { requiresAuth: true },
        components: {
          default: IndexMain,
          MainView: PublikasiView
        }
      },
      {
        path: "/tabel/:id/:web_id",
        name: "TabelView",
        meta: { requiresAuth: true },
        components: {
          default: IndexMain,
          MainView: TabelView
        }
      },
      {
        path: "/pekerjaan",
        name: "Pekerjaan",
        meta: { requiresAuth: true },
        components: {
          default: IndexMain,
          MainView: Pekerjaan
        }
      }
    ]
  },
  {
    path: "/login",
    name: "Login",
    meta: { guest: true },
    component: Login
  },
  {
    path: "*",
    name: "NotFound",
    component: NotFound
  }
];

const router = new VueRouter({
  mode: "history",
  routes
});

function loggedIn() {
  try {
    if (localStorage.getItem("apasi_cred") == null) {
      return false;
    }
    store.dispatch("indexMainStore/indexInit");
    return true;
  } catch (error) {
    return false;
  }
}

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (loggedIn()) {
      next();
    } else {
      next({
        path: "/login",
        query: { redirect: to.fullPath }
      });
    }
  } else if (to.matched.some(record => record.meta.guest)) {
    if (loggedIn()) {
      next({
        path: "/dashboard",
        query: { redirect: to.fullPath }
      });
    } else {
      next();
    }
  } else {
    next(); // make sure to always call next()!
  }
});

export default router;

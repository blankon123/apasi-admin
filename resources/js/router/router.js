import Vue from "vue";
import VueRouter from "vue-router";
import IndexMain from "./../components/views/main/IndexMain.vue";
import AccountData from "./../components/views/main/AccountData.vue";
import Dashboard from "./../components/views/main/Dashboard.vue";
import Publikasi from "./../components/views/main/Publikasi.vue";
import Tabel from "./../components/views/main/Tabel.vue";
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
        name: "Tabel",
        meta: { requiresAuth: true },
        components: {
          default: IndexMain,
          MainView: Tabel
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
  return axios.get("api/v1/user");
}

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    loggedIn()
      .then(() => {
        next();
      })
      .catch(() => {
        next({
          path: "/login",
          query: { redirect: to.fullPath }
        });
      });
  } else if (to.matched.some(record => record.meta.guest)) {
    loggedIn()
      .then(() => {
        next({
          path: "/dashboard",
          query: { redirect: to.fullPath }
        });
      })
      .catch(() => {
        next();
      });
  } else {
    next(); // make sure to always call next()!
  }
});

export default router;

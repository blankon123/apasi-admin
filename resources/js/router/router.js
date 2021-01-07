import Vue from 'vue'
import VueRouter from 'vue-router'
import IndexMain from './../components/views/main/IndexMain.vue'
import AccountData from './../components/views/main/AccountData.vue'
import Dashboard from './../components/views/main/Dashboard.vue'
import Login from './../components/views/auth/Login.vue'
import NotFound from './../components/views/error/NotFound.vue'

Vue.use(VueRouter)

const routes = [
  {
    path:"/",
    name:"Main",
    component:IndexMain,
    meta: { requiresAuth: true },
    children:[
      {
        path:"/account",
        name:"Account",
        meta: { requiresAuth: true },
        components:{
          default:IndexMain,
          MainView:AccountData
        }
      },{
        path:"/dashboard",
        name:"Dashboard",
        meta: { requiresAuth: true },
        components:{
          default:IndexMain,
          MainView:Dashboard
        }
      },
    ]
  },{
    path:"/login",
    name:"Login",
    meta: { guest: true },
    component:Login
  },{
    path:"*",
    name:"NotFound",
    component:NotFound
  },
]

const router = new VueRouter({
  mode:"history",
  routes
});

function loggedIn(){
  return false;
}

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!loggedIn()) {
      next({
        path: '/login',
        query: { redirect: to.fullPath }
      })
    } else {
      next()
    }
  } else if (to.matched.some(record => record.meta.guest)){
    if (loggedIn()) {
      next({
        path: '/dashboard',
        query: { redirect: to.fullPath }
      })
    } else {
      next()
    } // make sure to always call next()!
  } else{
    next() // make sure to always call next()!
  }
})

export default router;
import { createRouter, createWebHistory } from "vue-router";

import Login from "./pages/Login.vue";
import Register from "./pages/Register.vue";
import Profile from "./pages/Profile.vue";
import NotFound from "./pages/NotFound.vue";
import AuthCheck from "./components/AuthCheck.vue";

const routes = [
  { path: "/", component: AuthCheck },
  {
    path: "/login/:isFromRegistration?",
    component: Login,
    name: "login",
    props: (route) => ({ id: route.params.isFromRegistration || false }),
  },
  { path: "/register", component: Register, name: "register" },
  { path: "/profile/:phoneNumber?", component: Profile, name: "profile" },
  { path: "/:pathMatch(.*)", component: NotFound },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;

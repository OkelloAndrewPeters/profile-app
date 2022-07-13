import { createRouter, createWebHistory } from "vue-router";

import Login from "./pages/Login.vue"
import Register from "./pages/Register.vue"
import Profile from "./pages/Profile.vue"
import NotFound from "./pages/NotFound.vue"

const routes = [
    {path: "/login", component: Login },
    {path: "/register", component: Register },
    {path: "/profile", component: Profile },
    {path: "/:pathMatch(.*)", component: NotFound}
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router;
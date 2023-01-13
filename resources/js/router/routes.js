import {
    createRouter,
    createWebHashHistory
} from 'vue-router'
import Login from '../pages/components/contactus.vue';
import Dashboard from '../pages/main/dashboard.vue';

const routes = [{
        name: 'Login',
        path: '/login',
        component: Login,
    },
    {
        name: 'Dashboard',
        path: '/',
        component: Dashboard
    }

]

const router = createRouter({
    history: createWebHashHistory(),
    routes,
})

function loggedIn() {
    return localStorage.getItem('token')
}
router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!loggedIn()) {
            next({
                path: '/login',
                query: {
                    redirect: to.fullPath
                }
            })
        } else {
            next()
        }
    } else if (to.matched.some(record => record.meta.guest)) {
        if (loggedIn()) {
            next({
                path: '/',
                query: {
                    redirect: to.fullPath
                }
            })
        } else {
            next()
        }
    } else {
        next() // make sure to always call next()!
    }
});
export default router;

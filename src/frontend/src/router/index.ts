import Vue, {CreateElement} from 'vue'
import {Route} from 'vue-router'
import {Router, RouteConfig} from "@/components/router";

Vue.use(Router)

const routes: Array<RouteConfig> = [
    {
        component: { render: (h: CreateElement) => h('router-view') },
        path: '/',
        name: '',
        redirect: { name: 'login' },
        meta: {
            title: "Home",
            breadcrumb: "Home",
        },
        children: [
            {
                name: 'login',
                path: 'login',
                meta: {
                    title: "Login",
                },
                component: () => import(/* webpackChunkName: "home" */ '@/views/pages/Login.vue'),
            },
            {
                name: 'wallet',
                path: 'wallet',
                meta: {
                    title: "Wallet",
                    breadcrumb: "Wallet",
                },
                component: () => import(/* webpackChunkName: "wallet" */ '@/views/pages/Wallet.vue'),
            },
            {
                name: 'wallet-add',
                path: 'wallet-add',
                meta: {
                    title: "Wallet Add",
                    breadcrumb: "Wallet Add",
                },
                component: () => import(/* webpackChunkName: "wallet-add" */ '@/views/pages/WalletAdd.vue'),
            },
        ],
    },
]

const router = new Router({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})

export default router

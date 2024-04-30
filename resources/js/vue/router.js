import { createRouter, createWebHistory } from "vue-router";

import  { useCookies } from 'vue3-cookies'
const { cookies } = useCookies()

import List from './componets/ListComponent.vue'
import Save from './componets/SaveComponent.vue'
import Login from './componets/auth/LoginComponent.vue'

const routes = [
    {
        name: 'login',
        path: '/vue/login',
        component: Login
    },
    {
        name: 'list',
        path: '/vue',
        component: List
    },
    {
        name: 'save',
        path: '/vue/save/:slug?',
        component: Save
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes:routes
})

router.beforeEach(async(to, from, next) => {
    
    const auth = cookies.get('auth')
    
    if(!auth && to.name !== 'login'){
        return next({name:'login'})
    }
    
    next()

})

export default router
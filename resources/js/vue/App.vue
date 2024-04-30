<template lang="">
    <div>

        <nav class='bg-white border-b border-gray'>
            <header class='max-w-7xl px-4 sm:px-6 lg:px-8'>
                <div class='flex justify-between'>
                    <div class='flex items-center'>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="40"
                            height="35"
                            viewBox="0 0 262 227"
                            >
                            <g id="Vue.js_logo_strokes" fill="none" fill-rule="evenodd">
                                <g id="Path-2">
                                <polyline
                                    class="outer"
                                    stroke="#4B8"
                                    stroke-width="46"
                                    points="12.19 -24.031 131 181 250.351 -26.016"
                                />
                                </g>
                                <g id="Path-3" transform="translate(52)">
                                <polyline
                                    class="inner"
                                    stroke="#354"
                                    stroke-width="42"
                                    points="15.797 -14.056 79 94 142.83 -17.863"
                                />
                                </g>
                            </g>
                            </svg>
                    </div>
                    <div class='max-w-7xl mx-auto py-4 px-4 sm:px-6'>
                        <router-link :to="{'name': 'login'}" class='inline-flex uppercase border-b-2 text-sm-leading-5 mx-3 px-4 py-1 text-gray-600 text-center font-bold hover:text-gray-900 hover:border-gray-700 hover:-translate-y-1 durarion-150 transition-all' v-if="!$root.isLoggedIn">
                            Login
                        </router-link>
                    
                        <router-link :to="{'name': 'list'}" class='inline-flex uppercase border-b-2 text-sm-leading-5 mx-3 px-4 py-1 text-gray-600 text-center font-bold hover:text-gray-900 hover:border-gray-700 hover:-translate-y-1 durarion-150 transition-all' v-if="$root.isLoggedIn">
                            List
                        </router-link>

                        <o-button
                            v-if="$root.isLoggedIn"
                            variant="danger"
                            @click="logout"
                        >
                            Logout
                        </o-button>
                    </div>    
                    <div class='flex flex-col items-center py-2' v-if='$root.isLoggedIn'>   
                        <div class='rounded-full w-9 h-9 bg-blue-300 text-center p-1 font-bold'>
                            {{ $root.user.name.substr(0,2).toUpperCase() }}
                        </div>
                        <p>{{ $root.user.name }}</p>
                    </div>
                </div>
            </header>

        </nav>

        <!-- <o-button variant='danger' @click='logout'>
            Close Sesion
        </o-button> -->

        <router-view></router-view>
    </div>
</template>
<script>
export default {
    mounted() {

        if (window.Laravel && window.Laravel.isLoggedIn) {
            this.isLoggedIn = true
            this.user = window.Laravel.user
            this.token = window.Laravel.token

            this.$root.setCookieAuth({
                isLoggedIn: this.isLoggedIn,
                token: this.token,
                user: this.user,
            })
        } else {
            const auth = this.$cookies.get('auth')

            if (auth) {
                this.isLoggedIn = true
                this.user = auth.user
                this.token = auth.token
                // verification token
                this.$axios.post(this.$root.urls.tokenCheck, {
                    token: auth.token
                }).then(() => {
                    console.log('tokenCheck')
                }).catch(() => {
                    this.setCookieAuth('');
                    window.location.href = '/vue/login'
                })
            }
        }

        // const config = {
        //     headers: {
        //         Authorization: 'Bearer 19|2Lu2juLgTCW6rnUATamDpagq0y93Nb2pry5t779W72358518'
        //     }
        // }

        // axios.get('/api/user', config).then(response => {
        //     console.log(response.data)
        // })
    },
    methods: {
        setCookieAuth(data) {
            this.$cookies.set('auth', data)
        },
        logout() {

            const config = {
                headers: {
                    Authorization: 'Bearer ' + this.token
                }
            }

            axios.post('/api/user/logout', null, config)
                .then(() => {
                    this.setCookieAuth("")
                    window.location.href = '/vue'
                })
                .catch(() => {
                    window.location.href = '/vue'
                })
        }
    },
    data() {
        return {
            isLoggedIn: false,
            user: '',
            token: '',
            urls: {
                postUpload: '/api/post/upload/',
                postPaginate: '/api/post/',
                postPatch: '/api/post/',
                postPost: '/api/post/',
                postDelete: '/api/post/',
                getPostBySlug: '/api/post/slug/',
                getCategoriesAll: '/api/category/all',
                tokenCheck: '/api/user/token-check',
            }
        }
    },
}
</script>
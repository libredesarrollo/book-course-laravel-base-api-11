<template>

    <div class="min-h-screen flex flex-col sm:justify-center items-center bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white shadow-md overflow-hidden sm:rounded-md">
            <form @submit.prevent="submit">
                <o-field label="Username" :variant="errors.login ? 'danger' : 'primary'" :message="errors.login">
                    <o-input v-model="form.email"></o-input>
                </o-field>
                <o-field label="Password" :variant="errors.login ? 'danger' : 'primary'" :message="errors.login">
                    <o-input v-model="form.password" type="password"></o-input>
                </o-field>

                <o-button :disabled="disabledBotton" variant="primary" native-type="submit" class="float-right">Send</o-button>
            </form>
        </div>
    </div>

</template>
<script>
export default {
    created() {

        if (this.$root.isLoggedIn) {
            this.$router.push({ name: 'list' })
        }
    },
    data() {
        return {
            disabledBotton:false,
            form: {
                email: 'admin@admin.com',
                password: '12345',
            },
            errors: {
                login: ''
            },
        }
    },
    methods: {

        cleanErrorsForm() {
            this.errors.login = ''
        },

        submit() {
            this.cleanErrorsForm()
            this.disabledBotton=true
            axios.get('sanctum/csrf-cookie').then(response => {
                axios.post('/api/user/login', this.form).then(response => {

                    this.$root.setCookieAuth({
                        isLoggedIn: response.data.isLoggedIn,
                        token: response.data.token,
                        user: response.data.user,
                    })

                    setTimeout(() => (window.location.href = '/vue'), 1500)
                    // this.disabledBotton = false
                    this.$oruga.notification.open({
                        message: 'Login success',
                        position: 'bottom-right',
                        duration: 1000,
                        closable: true
                    })

                }).catch(error => {
                    this.disabledBotton=false
                    this.errors.login = error.response.data
                })
            })

        },
    }
}
</script>
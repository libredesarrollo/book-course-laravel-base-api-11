<template>
    <div class='container mx-auto'>
        <h3>Order Success: {{ session_id }}</h3>
        <hr>
        <div v-if="statusPayment == 0">
            <p>Processing...</p>
        </div>
        <div v-if="statusPayment == 1">
            <p>Orden proccess successfully</p>
        </div>
        <div v-if="statusPayment == 2">
            <p>One Error </p>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            session_id: '',
            statusPayment: 0,
            response: ''
        }
    },
    mounted() {
        this.processStripe()
    },
    methods: {
        processStripe() {
            // get session id from url
            let params = new URLSearchParams(document.location.search)
            this.session_id = params.get('session_id')
            // console.log(params.get('session_id'))

            // proccess stripe payment
            axios.get('/api/stripe/get-session/' + this.session_id).then(response => {
                this.statusPayment = 1
                this.response = response.data
            }).catch(error => {
                this.statusPayment = 2
                this.response = error
            })

        }
    }
}
</script>
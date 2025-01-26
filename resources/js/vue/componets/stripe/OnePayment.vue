<template>
  <div v-if="sessionID">
    <!-- whitout sessionID -->
    <!-- <StripeCheckout ref="checkoutRef" mode="payment" :pk="publishableKey" :line-items="lineItems"
      :successURL="successURL" :cancelURL="cancelURL" 
      @loading="v => loading = v" /> -->

    <!-- with sessionID -->
    <StripeCheckout :sessionId="sessionID" ref="checkoutRef" :pk="publishableKey" @loading="v => loading = v" />

    <button @click="submit">Pay</button>
  </div>
</template>
<script>
import { StripeCheckout } from '@vue-stripe/vue-stripe';

export default {
  components: {
    StripeCheckout
  },
  async mounted() {
    const res = await this.$axios.get('/api/stripe/create-session/' + this.lineItems[0].price)
    this.sessionID = res.data
    // console.log(this.sessionID)

    // const url = this.$router.resolve({
    //   name: 'list',
    //   // params: {

    //   // },
    //   // query: query,
    // }).fullPath
    // console.log(window.origin+ url)

  },
  data() {
    this.publishableKey = window.Laravel.clientStripe
    return {
      loading: false,
      sessionID: '',
      lineItems: [
        {
          price: 'price_1QYPNDCWud7Ri9mJPPPtwAnj', // recurrente
          quantity: 1
        },
        // {
        //   price: 'price_1QYPMsCWud7Ri9mJNjHwjq5s',
        //   quantity: 2
        // },
        // {
        //   price: 'price_1QYPKhCWud7Ri9mJwr0ZAFBP',
        //   quantity: 1
        // },
      ],
      successURL: 'http://laravelbaseapi.test/vue/stripe/success',
      cancelURL: 'http://laravelbaseapi.test/vue/stripe/cancel'
    }
  },
  methods: {
    submit() {
      this.$refs.checkoutRef.redirectToCheckout()
    }
  },
}
</script>
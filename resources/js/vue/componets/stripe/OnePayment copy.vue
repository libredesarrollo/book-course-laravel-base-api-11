<template>
    <div>

      <stripe-checkout
        
        ref="checkoutRef"
        mode="payment"
        :pk="publishableKey"
        :line-items="lineItems"
        :success-url="successURL"
        :cancel-url="cancelURL"
        @loading="v => loading = v"
      />
      <button :readonly="loading" @click="submit">Pay now!</button>
    </div>
  </template>
  
  <script>
  import { StripeCheckout } from '@vue-stripe/vue-stripe';
  export default {
    components: {
      StripeCheckout,
    },
    data () {
      this.publishableKey = 'pk_test_51QXJytCWud7Ri9mJLMxlPxHYRZykUZuL0lBVIHzMBUuYOQ31LdxtMEKaKlkfKgDrG41dLb0AWXRLl7nfMuIDw6Up00EbL6cWPF';
      return {
        loading: false,
        lineItems: [
          {
            price: 'price_1QYPKhCWud7Ri9mJwr0ZAFBP', // The id of the one-time price you created in your Stripe dashboard
            quantity: 1,
          },
        ],
        successURL: 'http://laravelbaseapi.test/vue/stripe/success',
        cancelURL: 'http://laravelbaseapi.test/vue/stripe/cancel',
      };
    },
    methods: {
      submit () {
        // You will be redirected to Stripe's secure checkout page
        this.$refs.checkoutRef.redirectToCheckout();
      },
    },
  };
  </script>
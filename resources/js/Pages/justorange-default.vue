
<script setup>
const checkout = () => {
  window.location.href = 'https://javaradigital.com/product/acs-secure-your-links-advertise-without-worries';
};
const goadmin = () => {
  window.location.href = '/dashboard';
}
</script>

<template>
  <section class="landing-page">
    <div class="container mx-auto py-16 text-center">
      <h1 class="text-4xl font-bold mb-4">HideIyh - Secure Shortlink Platform</h1>
      <p class="text-lg mb-8">
        Menyediakan produk <span class="font-semibold">Cloaking Signature</span> untuk keamanan dan privasi link Anda.
      </p>
      <button
        @click="checkout"
        class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-8 rounded shadow"
      >
        Checkout Sekarang
      </button>&nbsp;
      <button @click="goadmin"
      class="bg-orange-700 hover:bg-orange-400 text-white font-bold py-3 px-8 rounded shadow"
      >
      Login
    </button>
    
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';

// --- Fungsi dari permintaan Anda ---
const checkout = (planUrl = 'https://javaradigital.com/product/acs-secure-your-links-advertise-without-worries') => {
  window.location.href = planUrl;
};

const goadmin = () => {
  window.location.href = '/dashboard'; // Arahkan ke halaman login/dashboard
};

// --- Data untuk bagian dinamis ---
const features = ref([
  {
    name: 'Blocker',
    description: 'Blokir bot, mata-mata, dan lalu lintas yang tidak diinginkan secara efektif untuk melindungi kampanye iklan Anda.',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>`
  },
  {
    name: 'SmartURL',
    description: 'Buat tautan cerdas yang mengarahkan pengunjung berdasarkan aturan yang Anda tentukan, memaksimalkan konversi.',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>`
  },
  {
    name: 'Blocker API',
    description: 'Integrasikan sistem deteksi bot canggih kami ke dalam aplikasi Anda sendiri dengan API yang mudah digunakan.',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" /></svg>`
  },
  {
    name: 'Built-in WebApp',
    description: 'Kelola semua domain, tautan, dan pengaturan Anda dari satu dasbor web yang intuitif dan kuat.',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25A2.25 2.25 0 015.25 3h4.5M15 3.75l3 3m0 0l-3 3m3-3H9" /></svg>`
  },
  {
    name: 'MetaAds & GoogleAds Support',
    description: 'Didesain khusus untuk lolos dari tinjauan ketat Meta (Facebook) Ads dan Google Ads, amankan akun iklan Anda.',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`
  },
  {
    name: 'Geolocation API',
    description: 'Targetkan atau blokir pengunjung berdasarkan negara, wilayah, atau kota mereka untuk kampanye yang sangat relevan.',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>`
  }
]);

const testimonials = ref([
  {
    quote: "Layanan cloaking dari HideIyh.pw benar-benar mengubah cara saya beriklan. Akun Meta Ads saya sekarang jauh lebih aman dari AME. Wajib punya untuk para advertiser!",
    name: "Andi Wijaya",
    title: "Digital Marketer",
    avatar: "https://i.pravatar.cc/150?img=1"
  },
  {
    quote: "API Blocker-nya sangat kuat. Saya mengintegrasikannya ke web app saya dan berhasil memblokir 99% lalu lintas bot. Support-nya juga responsif dan sangat membantu.",
    name: "Citra Lestari",
    title: "Full-stack Developer",
    avatar: "https://i.pravatar.cc/150?img=5"
  },
  {
    quote: "Dengan kuota domain yang besar di paket Business, saya bisa mengelola semua kampanye klien saya dalam satu akun. Fitur geolocation juga sangat akurat.",
    name: "Budi Santoso",
    title: "Agency Owner",
    avatar: "https://i.pravatar.cc/150?img=8"
  }
]);

const faqs = ref([
    {
        question: "Apa itu cloaking dan mengapa saya membutuhkannya?",
        answer: "Cloaking adalah teknik menyajikan konten atau URL yang berbeda kepada audiens yang berbeda. Dalam periklanan, ini digunakan untuk melindungi landing page Anda dari peninjau iklan (reviewers) dan bot jahat, sehingga kampanye Anda lebih aman dan tahan lama."
    },
    {
        question: "Apakah layanan ini aman untuk Meta Ads dan Google Ads?",
        answer: "Ya, sistem kami, ACS (Advanced Cloaking Signature), dirancang khusus untuk memenuhi standar keamanan dan melewati sistem deteksi platform iklan besar seperti Meta Ads dan Google Ads."
    },
    {
        question: "Bagaimana cara kerja Blocker API?",
        answer: "Blocker API kami menyediakan endpoint yang dapat Anda panggil dari aplikasi Anda. Cukup kirimkan detail pengunjung (seperti IP, User Agent), dan API kami akan memberikan skor risiko dan rekomendasi apakah lalu lintas tersebut harus diblokir atau diizinkan."
    },
    {
        question: "Bisakah saya meng-upgrade paket saya nanti?",
        answer: "Tentu saja. Anda dapat meng-upgrade paket Anda kapan saja langsung dari dasbor pengguna Anda. Sistem akan menghitung biaya prorata secara otomatis."
    }
]);

const activeFaqIndex = ref(null);

const toggleFaq = (index) => {
    if (activeFaqIndex.value === index) {
        activeFaqIndex.value = null;
    } else {
        activeFaqIndex.value = index;
    }
};

</script>

<template>
  <div class="bg-gray-900 text-white font-sans">
    
    <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-50">
      <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="#" class="text-2xl font-bold text-orange-500">HideIyh.pw</a>
        <nav class="hidden md:flex space-x-8 items-center">
          <a href="#features" class="hover:text-orange-400 transition-colors">Features</a>
          <a href="#pricing" class="hover:text-orange-400 transition-colors">Pricing</a>
          <a href="#testimonials" class="hover:text-orange-400 transition-colors">Testimonials</a>
          <a href="#faq" class="hover:text-orange-400 transition-colors">FAQ</a>
        </nav>
        <button @click="goadmin" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-transform transform hover:scale-105">
          Login
        </button>
      </div>
    </header>

    <main>
      <section id="hero" class="relative overflow-hidden">
        <div class="container mx-auto px-6 py-24 md:py-32 text-center">
          <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 via-transparent to-transparent -z-10"></div>
          <h1 class="text-4xl md:text-6xl font-extrabold mb-4 leading-tight tracking-tight">
            Secure Your Links with Built-in
            <span class="text-orange-500">Bot Detection API</span>
          </h1>
          <p class="text-lg md:text-xl text-gray-300 max-w-3xl mx-auto mb-10">
            Platform cloaking terbaik untuk melindungi kampanye <span class="font-semibold">Meta Ads & Google Ads</span> Anda. Tingkatkan keamanan, privasi, dan performa iklan tanpa rasa khawatir.
          </p>
          <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
            <button @click="checkout()" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg text-lg transition-transform transform hover:scale-105 w-full sm:w-auto">
              Get Started Now
            </button>
            <button @click="goadmin" class="bg-transparent border-2 border-gray-600 hover:bg-gray-800 text-gray-300 font-bold py-3 px-8 rounded-lg text-lg transition-colors w-full sm:w-auto">
              Member Area
            </button>
          </div>
        </div>
      </section>

      <section id="features" class="py-20 bg-gray-950">
        <div class="container mx-auto px-6">
          <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Fitur Unggulan Kami</h2>
            <p class="text-gray-400 max-w-2xl mx-auto">
              Kami menyediakan semua alat yang Anda butuhkan untuk menjalankan kampanye iklan yang aman dan efisien.
            </p>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="feature in features" :key="feature.name" class="bg-gray-800/50 p-6 rounded-lg border border-gray-700 hover:border-orange-500 hover:-translate-y-2 transition-all duration-300">
              <div class="flex items-center mb-4">
                <div class="bg-orange-500/10 p-3 rounded-full mr-4 text-orange-400" v-html="feature.icon"></div>
                <h3 class="text-xl font-semibold">{{ feature.name }}</h3>
              </div>
              <p class="text-gray-400">
                {{ feature.description }}
              </p>
            </div>
          </div>
        </div>
      </section>
      
      <section id="pricing" class="py-20">
        <div class="container mx-auto px-6">
          <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Paket Harga yang Fleksibel</h2>
            <p class="text-gray-400 max-w-2xl mx-auto">
              Pilih paket yang paling sesuai dengan kebutuhan bisnis Anda. Tanpa biaya tersembunyi.
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-center">
            
            <div class="bg-gray-800 p-8 rounded-xl border border-gray-700 flex flex-col h-full">
              <h3 class="text-2xl font-bold mb-2">Basic</h3>
              <p class="text-gray-400 mb-6">Untuk pemula dan usaha kecil.</p>
              <ul class="space-y-4 mb-8 flex-grow">
                <li class="flex items-center"><span class="text-green-500 mr-3">✔</span> ACS Cloaking Script</li>
                <li class="flex items-center"><span class="text-green-500 mr-3">✔</span> 5 Domain Quota</li>
                <li class="flex items-center"><span class="text-green-500 mr-3">✔</span> 5.000 Visitor/Request/Day</li>
                <li class="flex items-center"><span class="text-green-500 mr-3">✔</span> Blocker</li>
                <li class="flex items-center"><span class="text-green-500 mr-3">✔</span> 24/7 Support</li>
              </ul>
              <button @click="checkout()" class="w-full mt-auto bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                Pilih Paket
              </button>
            </div>

            <div class="bg-gray-800 p-8 rounded-xl border-2 border-orange-500 relative transform scale-105 shadow-2xl shadow-orange-500/10 flex flex-col h-full">
              <div class="absolute top-0 -translate-y-1/2 left-1/2 -translate-x-1/2 bg-orange-500 text-white text-xs font-bold px-4 py-1 rounded-full uppercase">Most Popular</div>
              <h3 class="text-3xl font-bold mb-2 text-orange-400">Pro</h3>
              <p class="text-gray-400 mb-6">Solusi terbaik untuk profesional.</p>
              <ul class="space-y-4 mb-8 flex-grow">
                <li class="flex items-center"><span class="text-green-500 mr-3">✔</span> ACS Cloaking Script</li>
                <li class="flex items-center"><span class="text-green-500 mr-3">✔</span> 10 Domain Quota</li>
                <li class="flex items-center"><span class="text-green-500 mr-3">✔</span> 10.000 Visitor/Request/Day</li>
                <li class="flex items-center"><span class="text-green-500 mr-3">✔</span> API Blocker & API Geolocation</li>
                <li class="flex items-center"><span class="text-green-500 mr-3">✔</span> 24/7 Support</li>
              </ul>
              <button @click="checkout()" class="w-full mt-auto bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                Pilih Paket
              </button>
            </div>

            <div class="bg-gray-800 p-8 rounded-xl border border-gray-700 flex flex-col h-full">
              <h3 class="text-2xl font-bold mb-2">Business</h3>
              <p class="text-gray-400 mb-6">Untuk agensi dan skala besar.</p>
              <ul class="space-y-4 mb-8 flex-grow">
                <li class="flex items-center"><span class="text-green-500 mr-3">✔</span> ACS Cloaking Script</li>
                <li class="flex items-center"><span class="text-green-500 mr-3">✔</span> 50 Domain Quota</li>
                <li class="flex items-center"><span class="text-green-500 mr-3">✔</span> Unlimited Visitor/Request</li>
                <li class="flex items-center"><span class="text-green-500 mr-3">✔</span> API Blocker & API Geolocation</li>
                <li class="flex items-center"><span class="text-green-500 mr-3">✔</span> 24/7 Support</li>
              </ul>
              <button @click="checkout()" class="w-full mt-auto bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                Pilih Paket
              </button>
            </div>
          </div>
        </div>
      </section>

      <section id="testimonials" class="py-20 bg-gray-950">
        <div class="container mx-auto px-6">
          <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Apa Kata Mereka?</h2>
            <p class="text-gray-400 max-w-2xl mx-auto">
              Jangan hanya percaya kata kami. Lihat apa yang dikatakan pelanggan kami yang puas.
            </p>
          </div>
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div v-for="testimonial in testimonials" :key="testimonial.name" class="bg-gray-800 p-8 rounded-lg">
              <p class="text-gray-300 italic mb-6">"{{ testimonial.quote }}"</p>
              <div class="flex items-center">
                <img :src="testimonial.avatar" alt="avatar" class="w-12 h-12 rounded-full mr-4">
                <div>
                  <p class="font-semibold">{{ testimonial.name }}</p>
                  <p class="text-sm text-orange-400">{{ testimonial.title }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="faq" class="py-20">
        <div class="container mx-auto px-6 max-w-4xl">
          <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Pertanyaan yang Sering Diajukan</h2>
            <p class="text-gray-400">
              Punya pertanyaan? Kami punya jawabannya.
            </p>
          </div>
          <div class="space-y-4">
            <div v-for="(faq, index) in faqs" :key="index" class="bg-gray-800 rounded-lg overflow-hidden">
              <button @click="toggleFaq(index)" class="w-full text-left p-6 flex justify-between items-center hover:bg-gray-700/50 focus:outline-none">
                <span class="font-semibold text-lg">{{ faq.question }}</span>
                <span class="transition-transform duration-300" :class="{'rotate-45': activeFaqIndex === index, 'rotate-0': activeFaqIndex !== index}">
                   <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                </span>
              </button>
              <div class="transition-all duration-500 ease-in-out" :style="{ maxHeight: activeFaqIndex === index ? '200px' : '0px' }">
                <p class="p-6 pt-0 text-gray-300">{{ faq.answer }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <footer class="bg-gray-950 border-t border-gray-800">
      <div class="container mx-auto px-6 py-8 text-center text-gray-400">
        <p>&copy; {{ new Date().getFullYear() }} HideIyh.pw. All rights reserved.</p>
        <p class="text-sm mt-2">Powered by Javaradigital</p>
      </div>
    </footer>
    
  </div>
</template>

<style scoped>
/* Menambahkan beberapa style global jika diperlukan, seperti smooth scrolling */
html {
  scroll-behavior: smooth;
}
</style>
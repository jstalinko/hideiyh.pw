<script setup lang="js">
import { ref, onMounted, onBeforeUnmount,computed } from 'vue';
import { formatCurrency, imageUrl } from '../utils/helpers';

const prop = defineProps({props:Object});
// --- Dummy Data ---
const features = ref([
  { name: 'Blocker', description: 'Blokir bot, mata-mata, dan lalu lintas yang tidak diinginkan secara efektif untuk melindungi kampanye iklan Anda.', icon: 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z' },
  { name: 'SmartURL', description: 'Buat tautan cerdas yang mengarahkan pengunjung berdasarkan aturan yang Anda tentukan, memaksimalkan konversi.', icon: 'M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244' },
  { name: 'Blocker API', description: 'Integrasikan sistem deteksi bot canggih kami ke dalam aplikasi Anda sendiri dengan API yang mudah digunakan.', icon: 'M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z' },
  { name: 'Built-in WebApp', description: 'Kelola semua domain, tautan, dan pengaturan Anda dari satu dasbor web yang intuitif dan kuat.', icon: 'M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25A2.25 2.25 0 015.25 3h4.5M15 3.75l3 3m0 0l-3 3m3-3H9' },
  { name: 'MetaAds & GoogleAds Support', description: 'Didesain khusus untuk lolos dari tinjauan ketat Meta (Facebook) Ads dan Google Ads, amankan akun iklan Anda.', icon: 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
  { name: 'Geolocation API', description: 'Targetkan pengunjung berdasarkan negara, wilayah, atau kota mereka untuk kampanye yang sangat relevan.', icon: 'M15 10.5a3 3 0 11-6 0 3 3 0 016 0z M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z' }
]);
const pricingPlans = computed(() => {
  // Jika props.packages belum ada atau kosong, kembalikan array kosong
  if (!prop.props.packages || prop.props.packages.length === 0) {
    return [];
  }

  // Gunakan .map() untuk membuat array baru dengan data yang sudah diubah
  return prop.props.packages.map(pkg => {
    // A. BUAT ARRAY FITUR SECARA DINAMIS
    const features = [];
    if (pkg.feature_acs_cloaking_script) {
        features.push('ACS Cloaking Script');
    }

    if (pkg.domain_quota) {
        features.push(`${pkg.domain_quota} Domain Quota`);
    }
    if (pkg.visitor_quota_perday) {
        const visitorText = pkg.visitor_quota_perday === -1
            ? 'Unlimited visitor or api request'
            : `${pkg.visitor_quota_perday.toLocaleString('id-ID')} Visitor or api request / Day / Domain`;
        features.push(visitorText);
    }
    if (pkg.feature_api_blocker && pkg.feature_api_geolocation) {
        features.push('API Blocker & API Geolocation');
    } else if (pkg.feature_api_blocker) {
        features.push('API Blocker');
    }
    
    features.push('24/7 Support');

    return {
      ...pkg,

      highlighted: pkg.name === 'Pro',
      originalPrice: Math.round((pkg.price * 1.5) * 100) / 100,
      features: features 
    };
  });
});
const testimonials = computed(() => {
  if(!prop.props.testimonials || prop.props.testimonials.length === 0) return [];

  return prop.props.testimonials.map(pkg => {
    return {
      ...pkg,
      quote: pkg.content,
      title: pkg.jobs
    }
  });

});

const faqs = ref([
    { question: "Apa itu cloaking dan mengapa saya membutuhkannya?", answer: "Cloaking adalah teknik menyajikan konten yang berbeda kepada audiens yang berbeda. Dalam periklanan, ini digunakan untuk melindungi landing page Anda dari peninjau iklan (reviewers) dan bot jahat, sehingga kampanye Anda lebih aman." },
    { question: "Apakah layanan ini aman untuk Meta Ads dan Google Ads?", answer: "Ya, sistem kami dirancang khusus untuk memenuhi standar keamanan dan melewati sistem deteksi platform iklan besar seperti Meta Ads dan Google Ads." },
    { question: "Bagaimana cara kerja Blocker API?", answer: "Blocker API kami menyediakan endpoint yang dapat Anda panggil dari aplikasi Anda. Cukup kirimkan detail pengunjung, dan API kami akan memberikan skor risiko apakah lalu lintas tersebut harus diblokir." },
    { question: "Bisakah saya meng-upgrade paket saya nanti?", answer: "Tentu saja. Anda dapat meng-upgrade paket Anda kapan saja langsung dari dasbor pengguna Anda. Sistem akan menghitung biaya prorata secara otomatis." }
]);

// --- State for interactive elements ---
const isMobileMenuOpen = ref(false);
const activeFaqIndex = ref(null);
const sliderContainer = ref(null);

const toggleFaq = (index) => {
    activeFaqIndex.value = activeFaqIndex.value === index ? null : index;
};

const goadmin = () => window.location.href = '/dashboard';

// --- Slider Logic ---
const scrollTestimonials = (direction) => {
  if (sliderContainer.value) {
    const cardWidth = sliderContainer.value.querySelector('div').clientWidth;
    const scrollAmount = cardWidth * (window.innerWidth < 768 ? 1 : 2); // Scroll 1 card on mobile, 2 on desktop
    sliderContainer.value.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
  }
};
</script>

<template>
  <div class="bg-gray-900 text-white font-sans antialiased">
    
    <header class="bg-gray-900/70 backdrop-blur-lg sticky top-0 z-50 border-b border-gray-800">
      <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="#" class="text-2xl font-bold text-orange-500">HideIyh.pw</a>
        
        <nav class="hidden md:flex space-x-8 items-center">
          <a href="#features" class="hover:text-orange-400 transition-colors">Features</a>
          <a href="#pricing" class="hover:text-orange-400 transition-colors">Pricing</a>
          <a href="#testimonials" class="hover:text-orange-400 transition-colors">Testimonials</a>
          <a href="#faq" class="hover:text-orange-400 transition-colors">FAQ</a>
        </nav>
        
        <div class="hidden md:block">
          <button @click="goadmin" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-transform transform hover:scale-105">
            Login
          </button>
        </div>

        <div class="md:hidden">
          <button @click="isMobileMenuOpen = !isMobileMenuOpen">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
          </button>
        </div>
      </div>
      
      <div v-show="isMobileMenuOpen" class="md:hidden px-6 pb-4 space-y-2">
        <a href="#features" @click="isMobileMenuOpen = false" class="block py-2 hover:text-orange-400">Features</a>
        <a href="#pricing" @click="isMobileMenuOpen = false" class="block py-2 hover:text-orange-400">Pricing</a>
        <a href="#testimonials" @click="isMobileMenuOpen = false" class="block py-2 hover:text-orange-400">Testimonials</a>
        <a href="#faq" @click="isMobileMenuOpen = false" class="block py-2 hover:text-orange-400">FAQ</a>
        <button @click="goadmin" class="w-full mt-2 bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-lg">Login</button>
      </div>
    </header>

    <main>
      <section id="hero" class="relative overflow-hidden py-20 md:py-28">
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-900 to-orange-900/30 opacity-60"></div>
        <div class="container mx-auto px-6 relative z-10 grid md:grid-cols-2 gap-12 items-center">
          <div class="text-center md:text-left">
            <h1 class="text-4xl md:text-6xl font-extrabold mb-4 leading-tight">
              Secure Your Links with Built-in 
              <span class="text-orange-500">Bot Detection API</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-300 max-w-xl mx-auto md:mx-0 mb-10">
              Platform cloaking canggih untuk melindungi kampanye Anda dari bot, reviewer, dan trafik yang tidak diinginkan. HideIyh.pw menyediakan API deteksi bot, SmartURL, dan dashboard web yang intuitif untuk mengelola domain serta tautan Anda. Dirancang khusus agar lolos dari peninjauan Meta Ads & Google Ads, mendukung geotargeting, dan siap digunakan untuk bisnis skala kecil hingga besar.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
              <a href="#pricing" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg text-lg transition-transform transform hover:scale-105">
                Lihat Harga
              </a>
              <button @click="goadmin" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-bold py-3 px-8 rounded-lg text-lg transition-colors">
                Login
              </button>
            </div>
          </div>
          <div class="flex justify-center items-center">
            <svg viewBox="0 0 200 200" class="w-full max-w-sm h-auto">
              <defs>
                <filter id="glow">
                  <feGaussianBlur stdDeviation="2.5" result="coloredBlur"/>
                  <feMerge>
                    <feMergeNode in="coloredBlur"/>
                    <feMergeNode in="SourceGraphic"/>
                  </feMerge>
                </filter>
              </defs>
              <circle cx="100" cy="100" r="80" fill="none" stroke="#2d3748" stroke-width="2"/>
              <path d="M 100,20 A 80,80 0 0,1 100,180" fill="none" stroke="#dd6b20" stroke-width="4" stroke-linecap="round">
                <animateTransform attributeName="transform" type="rotate" from="0 100 100" to="360 100 100" dur="10s" repeatCount="indefinite" />
              </path>
              <g class="magnifying-glass">
                <circle cx="85" cy="85" r="25" fill="none" stroke="#dd6b20" stroke-width="3" filter="url(#glow)"/>
                <line x1="105" y1="105" x2="120" y2="120" stroke="#dd6b20" stroke-width="4" stroke-linecap="round" filter="url(#glow)"/>
              </g>
              <g fill="#4a5568">
                <circle cx="100" cy="90" r="10"/>
                <rect x="85" y="100" width="30" height="25" rx="5"/>
                <animate attributeName="opacity" values="0.5;1;0.5" dur="3s" repeatCount="indefinite" />
              </g>
            
            </svg>
          </div>
        </div>
      </section>

      <section id="features" class="py-20 bg-gray-950">
        <div class="container mx-auto px-6">
          <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Fitur Unggulan Kami</h2>
            <p class="text-gray-400 max-w-2xl mx-auto">Semua yang Anda butuhkan untuk menjalankan kampanye iklan yang aman dan efisien.</p>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="feature in features" :key="feature.name" class="bg-gray-800/50 p-6 rounded-xl border border-gray-700 hover:border-orange-500/50 hover:-translate-y-2 transition-all duration-300 group">
              <div class="flex items-center mb-4">
                <div class="bg-orange-500/10 p-3 rounded-full mr-4 text-orange-400">
                   <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" :d="feature.icon" /></svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-100">{{ feature.name }}</h3>
              </div>
              <p class="text-gray-400">{{ feature.description }}</p>
            </div>
          </div>
        </div>
      </section>
      
  <section id="pricing" class="py-20">
        <div class="container mx-auto px-6">
          <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Paket Harga yang Sesuai</h2>
            <p class="text-gray-400 max-w-2xl mx-auto">Pilih paket yang paling sesuai dengan kebutuhan bisnis Anda. Tanpa biaya tersembunyi.</p>
          </div>
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
            <div v-for="plan in pricingPlans" :key="plan.name" :class="['bg-gray-800 p-8 rounded-xl border flex flex-col h-full transition-transform', plan.highlighted ? 'border-2 border-orange-500 transform lg:scale-105 shadow-2xl shadow-orange-500/10' : 'border-gray-700']">
              <div v-if="plan.highlighted" class="absolute top-0 -translate-y-1/2 left-1/2 -translate-x-1/2 bg-orange-500 text-white text-xs font-bold px-4 py-1 rounded-full uppercase tracking-wider">Most Popular</div>
              
              <h3 class="text-2xl font-bold mb-2" :class="{'text-orange-400': plan.highlighted}">{{ plan.name }}</h3>
              <p class="text-gray-400 mb-4">{{ plan.description }}</p>

              <div class="my-5">
                <div class="flex items-baseline justify-center">
                    
                    <span class="text-4xl font-extrabold" :class="{'text-3xl': plan.highlighted}">{{ formatCurrency(plan.price) }}</span>
                    <span class="ml-1 text-xl font-semibold text-gray-400">/{{ plan.billing_cycle }}</span>
                </div>
                <div class="text-center h-6 mt-1"> <span v-if="plan.originalPrice" class="line-through text-gray-500 text-lg">
                    ${{ plan.originalPrice }}
                  </span>
                </div>
              </div>

              <ul class="space-y-4 mb-8 flex-grow">
                <li v-for="feature in plan.features" :key="feature" class="flex items-start">
                  <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                  <span>{{ feature }}</span>
                </li>
              </ul>
              <a :href="'/plan/'+plan.name.toLowerCase()" target="_blank" :class="['w-full mt-auto font-bold py-3 px-6 rounded-lg transition-colors', plan.highlighted ? 'bg-orange-500 hover:bg-orange-600 text-white' : 'bg-gray-700 hover:bg-gray-600 text-gray-200']">
                Pilih Paket
              </a>
            </div>
          </div>
        </div>
      </section>
      <section id="testimonials" class="py-20 bg-gray-950">
        <div class="container mx-auto px-6">
          <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Apa Kata Mereka?</h2>
            <p class="text-gray-400 max-w-2xl mx-auto">Lihat apa yang dikatakan pelanggan kami yang puas.</p>
          </div>
          <div class="relative">
            <div ref="sliderContainer" class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth scrollbar-hide -mx-3">
              <div v-for="(testimonial, index) in testimonials" :key="index" class="flex-shrink-0 w-full md:w-1/4 snap-center p-3">
                <div class="bg-gray-800 p-8 rounded-lg h-full flex flex-col justify-between">
                  <div class="text-gray-300 italic mb-6 prose prose-stone">"{{ testimonial.quote }}"</div>
                  <div class="flex items-center mt-auto">
                    <img :src="imageUrl(testimonial.avatar)" alt="avatar" class="w-12 h-12 rounded-full mr-4">
                    <div>
                      <p class="font-semibold text-white">{{ testimonial.name }}</p>
                      <p class="text-sm text-orange-400">{{ testimonial.title }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <button @click="scrollTestimonials(-1)" class="absolute top-1/2 -translate-y-1/2 left-0 -translate-x-4 bg-gray-700 hover:bg-orange-500 rounded-full p-2 text-white z-10 hidden md:block transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button @click="scrollTestimonials(1)" class="absolute top-1/2 -translate-y-1/2 right-0 translate-x-4 bg-gray-700 hover:bg-orange-500 rounded-full p-2 text-white z-10 hidden md:block transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
          </div>
        </div>
      </section>

      <section id="faq" class="py-20">
        <div class="container mx-auto px-6 max-w-4xl">
          <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Pertanyaan yang Sering Diajukan</h2>
          </div>
          <div class="space-y-4">
            <div v-for="(faq, index) in faqs" :key="index" class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
              <button @click="toggleFaq(index)" class="w-full text-left p-6 flex justify-between items-center hover:bg-gray-700/50 focus:outline-none transition-colors">
                <span class="font-semibold text-lg">{{ faq.question }}</span>
                <span class="transition-transform duration-300" :class="{'rotate-45': activeFaqIndex === index}">
                   <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                </span>
              </button>
              <div class="overflow-hidden transition-all duration-500 ease-in-out" :style="{ maxHeight: activeFaqIndex === index ? '200px' : '0px' }">
                <p class="p-6 pt-0 text-gray-300">{{ faq.answer }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <footer class="bg-gray-950 border-t border-gray-800">
      <div class="container mx-auto px-6 py-8 text-center text-gray-400">
        <p>&copy; {{ new Date().getFullYear() }} HideIyh.pw. All Rights Reserved.</p>
        <p class="text-sm mt-2">Powered by Javaradigital</p>
      </div>
    </footer>
  </div>
</template>
  <style>
                .magnifying-glass {
                  animation: search 4s ease-in-out infinite;
                  transform-origin: center;
                }
                @keyframes search {
                  0% { transform: translate(0, 0) rotate(0deg); }
                  25% { transform: translate(15px, -10px) rotate(15deg); }
                  75% { transform: translate(-10px, 15px) rotate(-10deg); }
                  100% { transform: translate(0, 0) rotate(0deg); }
                }
              </style>
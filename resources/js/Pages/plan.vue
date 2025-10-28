<script setup lang="js">
import { ref, computed, defineProps ,onMounted} from 'vue'; // <-- 1. TAMBAHKAN defineProps
import { formatCurrency } from '../utils/helpers';

// --- PROPS ---
// 2. Tentukan prop yang diterima dari komponen induk
const prop = defineProps({props:Object});



const packages = computed(() => {
  // Pastikan props.packages ada dan bukan array kosong
  if (!prop.props.packages || prop.props.packages.length === 0) {
    return [];
  }

  // Gunakan .map() untuk mengubah setiap item di array
  return prop.props.packages.map((pkg) => {
    
    // --- Membangun daftar fitur secara dinamis ---
    const featureList = [];

    
    featureList.push(`${pkg.domain_quota} Domain Quota`);
    
    if (pkg.visitor_quota_perday === -1) {
      featureList.push('Unlimited Visitor/Request');
    } else {
    
      featureList.push(`${pkg.visitor_quota_perday.toLocaleString('id-ID')} Request/Day/Domain`);
    }

    
    if (pkg.feature_api_blocker && pkg.feature_api_geolocation) {
      featureList.push('API Blocker & Geolocation');
    } else if (pkg.feature_api_blocker) {
      featureList.push('Blocker'); // Untuk paket 'Basic'
    }
    
    
    featureList.push('24/7 Support');
    
    return {
      id: pkg.name.toLowerCase(), // 'Basic' -> 'basic'
      name: pkg.name,
      price: parseFloat(pkg.price),
      originalPrice: Math.round((pkg.price * 1.5) * 100) / 100, // Asumsi diskon
      features: featureList
    };
  });
});



// --- STATE ---
// 3. Gunakan prop untuk mengatur nilai awal
const selectedPackageId = ref(prop.props.packageActive.name.toLowerCase()); 
const customerDetails = ref(prop.props.customerDetails);
const isLoading = ref(false);
// --- 3. TAMBAHKAN STATE BARU UNTUK PAYMENT ---
const paymentChannels = ref([]); // Akan diisi oleh data dari API
const isPaymentLoading = ref(true);
const paymentError = ref(null);
const selectedPaymentMethodId = ref(null);
const openPaymentGroup = ref(null);


// --- COMPUTED PROPERTIES ---
const currentPackage = computed(() => {
    return packages.value.find(p => p.id === selectedPackageId.value);
});

// --- METHODS ---
function selectPackage(packageId) {
    selectedPackageId.value = packageId;
}

function selectPaymentMethod(methodId) {
    selectedPaymentMethodId.value = methodId;
}
async function processCheckout() {
    // 1. Validasi form (tetap sama)
    if (!customerDetails.value.name || !customerDetails.value.email || !customerDetails.value.phone) {
        alert('Harap isi semua detail informasi Anda.');
        return;
    }

    isLoading.value = true;

    // 2. Siapkan data untuk dikirim (Payload)
    // Kita perlu mencari nama metode pembayaran dari ID yang dipilih
    const selectedMethod = paymentChannels.value.find(
        (m) => m.id === selectedPaymentMethodId.value
    );

    if (!selectedMethod) {
        alert('Metode pembayaran tidak valid. Harap pilih ulang.');
        isLoading.value = false;
        return;
    }

    const postData = {
        price: currentPackage.value.price,
        methodCode: selectedPaymentMethodId.value, // Ini adalah 'code'
        methodName: selectedMethod.name,         // Ini adalah 'name'
        phone: customerDetails.value.phone,
        email: customerDetails.value.email,
        name: customerDetails.value.name,
    };

    console.log('--- MENGIRIM DATA CHECKOUT ---', postData);

    // 3. Kirim request ke API menggunakan try...catch
    try {
        const response = await fetch('https://javaradigital.com/api/transaction/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(postData), // Ubah data JS menjadi string JSON
        });

        const result = await response.json(); // Ambil respons sebagai JSON

        if (!response.ok) {
            // Tangani error dari API (misal: status 400 atau 500)
            throw new Error(result.message || 'Terjadi kesalahan pada server.');
        }

        // 4. Tangani respons sukses
        console.log('API Response:', result);
        
        if (result.success && result.data) {
            window.location.href = '/plan/invoice/'+result.data.merchant_ref;
        } else {
            // Fallback jika respons tidak memiliki payment_url
            alert(`Checkout untuk paket ${currentPackage.value.name} berhasil! Silakan cek email Anda.`);
        }

    } catch (error) {
        // 5. Tangani error (misal: jaringan gagal, API tidak bisa dijangkau)
        console.error('Checkout Error:', error);
        alert(`Terjadi kesalahan saat checkout: ${error.message}`);
    } finally {
        // 6. Selalu hentikan loading, baik sukses maupun gagal
        isLoading.value = false;
    }
}

const groupedPaymentChannels = computed(() => {
    // Gunakan reduce untuk mengubah array datar menjadi objek yang dikelompokkan
    return paymentChannels.value.reduce((acc, channel) => {
        const group = channel.description; // 'description' berisi nama grup (misal: 'Virtual Account')
        if (!acc[group]) {
            acc[group] = []; // Buat array baru jika grup ini belum ada
        }
        acc[group].push(channel); // Masukkan channel ke grup yang sesuai
        return acc;
    }, {});
});

function togglePaymentGroup(groupName) {
    if (openPaymentGroup.value === groupName) {
        openPaymentGroup.value = null; // Tutup jika diklik lagi
    } else {
        openPaymentGroup.value = groupName; // Buka grup yang baru
    }
}

async function fetchPaymentChannels() {
  try {
    isPaymentLoading.value = true;
    paymentError.value = null;
    
    const response = await fetch('https://javaradigital.com/api/payment-channel');
    if (!response.ok) throw new Error(`Gagal mengambil data: ${response.statusText}`);
    
    const data = await response.json();
    
    if (data.success && data.data) {
      paymentChannels.value = data.data
        .filter(channel => channel.active)
        .map(channel => ({
          id: channel.code,
          name: channel.name,
          description: channel.group, // Kita gunakan 'group' sebagai deskripsi
          logo: channel.icon_url
        }));

      // Atur metode pembayaran default
      if (paymentChannels.value.length > 0) {
        const defaultQRIS = paymentChannels.value.find(p => p.id === 'QRIS2');
        if (defaultQRIS) {
          selectedPaymentMethodId.value = 'QRIS2';
          // ✅ MODIFIKASI: Buka grup yang berisi QRIS secara default
          openPaymentGroup.value = defaultQRIS.description; 
        } else {
          const firstMethod = paymentChannels.value[0];
          selectedPaymentMethodId.value = firstMethod.id;
          // ✅ MODIFIKASI: Buka grup item pertama sebagai fallback
          openPaymentGroup.value = firstMethod.description;
        }
      }
    } else {
      throw new Error(data.message || 'Format data API tidak valid');
    }
  } catch (error) {
    console.error(error);
    paymentError.value = error.message;
  } finally {
    isPaymentLoading.value = false;
  }
}

// --- 6. PANGGIL FUNGSI SAAT KOMPONEN DIMUAT ---
onMounted(() => {
  fetchPaymentChannels();
});
</script>

<template>
  <div class="bg-gray-900 min-h-screen text-white font-sans antialiased">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16">
      
      <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">Checkout</h1>
        <p class="mt-4 text-lg text-gray-400">Selesaikan pesanan Anda dalam beberapa langkah mudah.</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">
        
        <div class="lg:col-span-3 space-y-10">
          
          <div class="bg-gray-800/50 border border-gray-700 rounded-xl p-6">
            <h2 class="text-2xl font-bold mb-5 flex items-center">
              <span class="bg-orange-500 text-white rounded-full h-8 w-8 flex items-center justify-center mr-3 font-mono">1</span>
              Pilih Paket Anda
            </h2>
            <div class="grid sm:grid-cols-3 gap-4">
              <div v-for="pkg in packages" :key="pkg.id"
                   @click="selectPackage(pkg.id)"
                   :class="[
                     'p-4 border-2 rounded-lg cursor-pointer transition-all duration-200 text-center',
                     selectedPackageId === pkg.id ? 'border-orange-500 bg-orange-500/10 ring-2 ring-orange-500' : 'border-gray-600 hover:border-orange-400'
                   ]">
                <h3 class="font-bold text-lg">{{ pkg.name }}</h3>
                <p class="text-xl font-semibold mt-1">{{ formatCurrency(pkg.price) }}</p>
                <p class="text-xs text-gray-400 line-through">{{ formatCurrency(pkg.originalPrice) }}</p>
              </div>
            </div>
          </div>

          <div class="bg-gray-800/50 border border-gray-700 rounded-xl p-6">
            <h2 class="text-2xl font-bold mb-5 flex items-center">
              <span class="bg-orange-500 text-white rounded-full h-8 w-8 flex items-center justify-center mr-3 font-mono">2</span>
              Detail Informasi Anda
            </h2>
            
            <div class="space-y-4">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Nama Lengkap</label>
                <input v-model="customerDetails.name" type="text" id="name" placeholder="Nama Anda" class="w-full bg-gray-700 border-gray-600 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 p-2">
              </div>
              <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Alamat Email</label>
                <input v-model="customerDetails.email" type="email" id="email" placeholder="email@anda.com" class="w-full bg-gray-700 border-gray-600 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 p-2">
              </div>
              <div>
                <label for="phone" class="block text-sm font-medium text-gray-300 mb-1">Nomor Handphone</label>
                <input v-model="customerDetails.phone" type="tel" id="phone" placeholder="081234567890" class="w-full bg-gray-700 border-gray-600 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500  p-2">
              </div>
            </div>
          </div>

     
   <div class="bg-gray-800/50 border border-gray-700 rounded-xl p-6">
  <h2 class="text-2xl font-bold mb-5 flex items-center">
    <span class="bg-orange-500 text-white rounded-full h-8 w-8 flex items-center justify-center mr-3 font-mono">3</span>
    Metode Pembayaran
  </h2>

  <div v-if="isPaymentLoading" class="text-center py-8">
    <svg class="animate-spin h-8 w-8 text-orange-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    <p class="mt-2 text-gray-400">Memuat metode pembayaran...</p>
  </div>

  <div v-else-if="paymentError" class="text-center py-8 text-red-400">
    <p><strong>Oops! Gagal memuat pembayaran.</strong></p>
    <p class="text-sm">{{ paymentError }}</p>
  </div>

  <div v-else class="space-y-3">
    <div v-for="(channels, groupName) in groupedPaymentChannels" :key="groupName" class="border border-gray-700 rounded-lg overflow-hidden">
      
      <button
        type="button"
        @click="togglePaymentGroup(groupName)"
        class="w-full flex justify-between items-center p-4 bg-gray-700/50 hover:bg-gray-700 transition-colors"
      >
        <span class="font-semibold text-lg">{{ groupName }}</span>
        <svg
          class="w-5 h-5 transition-transform"
          :class="{ 'rotate-180': openPaymentGroup === groupName }"
          xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
        >
          <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>
      </button>

      <div v-show="openPaymentGroup === groupName" class="p-4 space-y-3">
        <div v-for="method in channels" :key="method.id"
             @click="selectPaymentMethod(method.id)"
             :class="[
               'flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all duration-200',
               selectedPaymentMethodId === method.id ? 'border-orange-500 bg-orange-500/10' : 'border-gray-600 hover:border-gray-500'
             ]">
          <img :src="method.logo" :alt="method.name" class="h-8 w-auto object-contain mr-4 bg-white p-1 rounded">
          <div>
            <p class="font-semibold">{{ method.name }}</p>
            </div>
          <div class="ml-auto">
            <div class="h-5 w-5 rounded-full border-2 flex items-center justify-center" :class="[selectedPaymentMethodId === method.id ? 'border-orange-500' : 'border-gray-500']">
              <div v-if="selectedPaymentMethodId === method.id" class="h-2.5 w-2.5 rounded-full bg-orange-500"></div>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</div> 

        </div>

        <div class="lg:col-span-2">
          <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 lg:sticky lg:top-24">
            <h2 class="text-2xl font-bold mb-5 border-b border-gray-700 pb-4">Ringkasan Pesanan</h2>
            
            <div v-if="currentPackage" class="space-y-4">
              <div class="flex justify-between items-center">
                <p class="text-lg font-semibold">Paket {{ currentPackage.name }}</p>
                <p class="text-lg font-semibold">{{ formatCurrency(currentPackage.price)  }}</p>
              </div>

              <div class="text-sm text-gray-300 pt-2">
                <p class="font-semibold mb-2">Fitur Termasuk:</p>
                <ul class="space-y-1.5 pl-1">
                  <li v-for="feature in currentPackage.features" :key="feature" class="flex items-start">
                    <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ feature }}</span>
                  </li>
                </ul>
              </div>

              <div class="border-t border-gray-700 pt-4 space-y-2">
                <div class="flex justify-between text-gray-300">
                  <p>Subtotal</p>
                  <p>{{ formatCurrency(currentPackage.price) }}</p>
                </div>
                <div class="flex justify-between text-gray-300">
                  <p>Pajak</p>
                  <p>$0.00</p>
                </div>
              </div>

              <div class="border-t-2 border-dashed border-gray-600 pt-4">
                <div class="flex justify-between text-white text-xl font-bold">
                  <p>Total</p>
                  <p>{{ formatCurrency(currentPackage.price) }}</p>
                </div>
              </div>

              <div class="pt-6">
                <button @click="processCheckout" :disabled="isLoading"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg text-lg transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                  <svg v-if="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <span>{{ isLoading ? 'Memproses...' : 'Checkout Sekarang' }}</span>
                </button>
              </div>
            </div>
            
          </div>
        </div>

      </div>

    </div>
  </div>
</template>
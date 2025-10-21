<script setup lang="js">
import { ref, onMounted, computed } from 'vue';


// --- STATE ---
const invoiceData = ref(null);
const isLoading = ref(true);
const error = ref(null);
const prop = defineProps({ props: Object });
const invoiceId = ref(prop.props.invoiceId); // Ambil invoiceId dari parameter route

// --- HELPER FUNCTIONS ---
/**
 * Memformat angka menjadi format mata uang Rupiah.
 */
function formatCurrency(amount) {
    if (typeof amount !== 'number') return 'Rp 0';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0, // Tidak menampilkan desimal
        maximumFractionDigits: 0,
    }).format(amount);
}

const openInstructionIndex = ref(0); // <-- BARU: Default buka instruksi pertama

// ... (Sisa state Anda) ...

// --- BARU: Fungsi untuk toggle accordion instruksi ---
function toggleInstruction(index) {
    if (openInstructionIndex.value === index) {
        openInstructionIndex.value = null; // Tutup jika diklik lagi
    } else {
        openInstructionIndex.value = index; // Buka item yang baru
    }
}
/**
 * Mendapatkan kelas warna badge berdasarkan status.
 */
const statusBadgeClass = computed(() => {
    if (!invoiceData.value) return 'bg-gray-500';
    switch (invoiceData.value.status.toUpperCase()) {
        case 'PAID': return 'bg-green-500 text-green-100';
        case 'UNPAID': return 'bg-yellow-500 text-yellow-100';
        case 'EXPIRED': return 'bg-red-500 text-red-100';
        case 'FAILED': return 'bg-red-600 text-red-100';
        default: return 'bg-gray-500 text-gray-100';
    }
});

// --- API FETCH LOGIC ---
async function fetchInvoiceData() {
    isLoading.value = true;
    error.value = null;
    invoiceData.value = null; // Reset data sebelum fetch baru

    if (!invoiceId.value) {
        error.value = 'ID Invoice tidak ditemukan di URL.';
        isLoading.value = false;
        return;
    }

    try {
        const response = await fetch(`https://javaradigital.com/api/transaction/${invoiceId.value}`);

        if (!response.ok) {
            const errorData = await response.json().catch(() => ({ message: 'Gagal mengambil data invoice.' }));
            throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success && data.data) {
            invoiceData.value = data.data;
        } else {
            throw new Error(data.message || 'Format data API tidak valid atau transaksi tidak ditemukan.');
        }
    } catch (err) {
        console.error('Fetch error:', err);
        error.value = err.message;
    } finally {
        isLoading.value = false;
    }
}

// --- LIFECYCLE HOOK ---
onMounted(() => {
    fetchInvoiceData();
});
</script>

<template>
    <div class="bg-gray-900 min-h-screen text-white font-sans antialiased py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">

            <div v-if="isLoading" class="text-center py-20">
                <svg class="animate-spin h-12 w-12 text-orange-500 mx-auto" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <p class="mt-4 text-lg text-gray-400">Memuat detail invoice...</p>
            </div>

            <div v-else-if="error"
                class="bg-red-900/50 border border-red-700 text-red-300 px-6 py-5 rounded-lg text-center">
                <h2 class="text-xl font-bold mb-2">Oops! Terjadi Kesalahan</h2>
                <p>{{ error }}</p>
                <button @click="fetchInvoiceData"
                    class="mt-4 bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded transition-colors">
                    Coba Lagi
                </button>
            </div>

            <div v-else-if="invoiceData"
                class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-700">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                        <div>
                            <h1 class="text-2xl font-bold tracking-tight">Invoice #{{ invoiceData.merchant_ref }}</h1>
                            <p class="text-sm text-gray-400 mt-1">Detail Pembayaran</p>
                        </div>
                        <span :class="['text-sm font-semibold px-3 py-1 rounded-full mt-3 sm:mt-0', statusBadgeClass]">
                            {{ invoiceData.status }}
                        </span>
                    </div>
                </div>

                <div class="px-6 py-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-400">Total Pembayaran</dt>
                            <dd class="mt-1 text-2xl font-bold text-orange-400">
                                {{ formatCurrency(invoiceData.amount) }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-400">Metode Pembayaran</dt>
                            <dd class="mt-1 text-lg font-semibold flex items-center">
                                {{ invoiceData.payment_name }}
                            </dd>
                        </div>
                    </div>

                    <div v-if="invoiceData.qr_url" class="text-center border-t border-gray-700 pt-6">
                        <h3 class="text-lg font-semibold mb-3">Scan QR Code</h3>
                        <img :src="invoiceData.qr_url" alt="QR Code Pembayaran"
                            class="mx-auto bg-white p-2 rounded-md max-w-[200px] h-auto">
                        <p v-if="invoiceData.expired_time" class="text-xs text-gray-400 mt-2">
                            Kode QR akan kedaluwarsa
                        </p>
                    </div>


                    <div class="border-t border-gray-700 pt-6">
                        <h3 class="text-lg font-semibold mb-4">Instruksi Pembayaran</h3>
                        <div class="space-y-2">
                            <div v-for="(instruction, index) in invoiceData.instructions" :key="index"
                                class="border border-gray-700 rounded-lg overflow-hidden">

                                <button type="button" @click="toggleInstruction(index)"
                                    class="w-full flex justify-between items-center p-4 bg-gray-700/50 hover:bg-gray-700 transition-colors text-left">
                                    <span class="font-semibold text-gray-200">{{ instruction.title }}</span>
                                    <svg class="w-5 h-5 transition-transform flex-shrink-0 text-gray-400"
                                        :class="{ 'rotate-180': openInstructionIndex === index }"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>

                                <div v-show="openInstructionIndex === index" class="p-4 border-t border-gray-600">
                                    <ol class="list-decimal list-inside space-y-1.5 text-sm text-gray-300">
                                        <li v-for="(step, stepIndex) in instruction.steps" :key="stepIndex">
                                            {{ step }}
                                        </li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div v-if="invoiceData.status === 'UNPAID'"
                        class="border-t border-gray-700 pt-6 text-center">
                        <a href="" target="_blank"
                            class="inline-block bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg text-lg transition-colors">
                            Check Payment
                        </a>
                    </div>

                </div>
            </div>

            <div v-else class="text-center py-20 text-gray-500">
                <p>Invoice tidak ditemukan.</p>
            </div>

        </div>
    </div>
</template>
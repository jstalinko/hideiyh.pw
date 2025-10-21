<template>
    <div class="min-h-screen bg-base-200 flex justify-center items-center p-4">
      <div class="card w-full max-w-2xl bg-base-100 shadow-xl">
        <div class="card-body">
          <!-- Invoice Header -->
          <div class="flex justify-between items-center">
            <div>
              <h1 class="text-3xl font-bold">INVOICE</h1>
              <p class="text-sm opacity-70">#{{ invoiceNumber }}</p>
            </div>
            <div class="text-right">
              <p class="text-sm opacity-70">{{ invoiceDate }}</p>
              <div class="badge" :class="statusBadgeClass">{{ invoice.status }}</div>
            </div>
          </div>
          
          <div class="divider"></div>
          
          <!-- Client Information -->
          <div>
            <h2 class="text-xl font-semibold">Client Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
              <div class="form-control">
                <label class="label">
                  <span class="label-text">Email</span>
                </label>
                <div class="input input-bordered flex items-center px-4">
                  <span>{{ invoice.email }}</span>
                </div>
              </div>
              <div class="form-control">
                <label class="label">
                  <span class="label-text">Phone</span>
                </label>
                <div class="input input-bordered flex items-center px-4">
                  <span>{{ invoice.phone }}</span>
                </div>
              </div>
            </div>
          </div>
          
         <!-- Transfer Payment Section -->
        <div>
          <h2 class="text-xl font-semibold">Transfer Pembayaran</h2>
         
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/799px-Bank_Central_Asia.svg.png" alt="Bank BCA" style="max-width: 120px;" class="m-3"/>
            
              <div class="grid grid-cols-1 gap-2">
                <div>
                  <p class="text-sm font-medium text-base-content/60">Nomor Rekening</p>
                  <p class="font-bold text-lg"> 2470952426 </p>
                </div>
                <div>
                  <p class="text-sm font-medium text-base-content/60">Atas Nama</p>
                  <p class="font-medium">PT JEPARA SOLUSI TEKNOLOGI</p>
                </div>
                <div>
                  <p class="text-sm font-medium text-base-content/60">Total Bayar</p>
                  <p class="font-bold text-xl text-primary">{{ formatPrice(invoice.totalBayar) }}</p>
                </div>
             
          </div>
        </div>

        
          
          <div class="divider"></div>
          
          <!-- Invoice Details -->
          <div>
            <h2 class="text-xl font-semibold">Invoice Details</h2>
            <div class="overflow-x-auto mt-4">
              <table class="table w-full">
                <thead>
                  <tr>
                    <th>Description</th>
                    <th class="text-right">Amount</th>
                  </tr>
                </thead>
                <tbody>
                 
                    <tr>
                        <td>Order add domain </td>
                        <td class="text-right">{{ invoice.amount }} Domain</td>
                    </tr>
                  <tr>
                    <td>Kode Unik</td>
                    <td class="text-right">{{ formatPrice(invoice.kodeUnik) }}</td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Sub-Total</th>
                    <th class="text-right">{{ formatPrice(invoice.subTotal) }}</th>
                  </tr>
                  <tr>
                    <th>Total Bayar</th>
                    <th class="text-right text-lg">{{ formatPrice(invoice.totalBayar) }}</th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          
          <div class="divider"></div>
          
          <!-- Payment Status -->
          <div>
            <div class="flex justify-between items-center">
              <h2 class="text-xl font-semibold">Payment Status</h2>
              <div class="badge badge-lg" :class="statusBadgeClass">{{ invoice.status }}</div>
            </div>
            <div class="mt-6">
              <button class="btn btn-primary w-full" @click="handlePayment">Confirm Payment</button>
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
  const $prop = defineProps({props: Object});
  // Data
  
  const invoiceNumber = $prop.props.domain.invoice;
  const invoiceDate = $prop.props.domain.created_at;
  const invoice = ref({
    amount: $prop.props.domain.amount,
    email: $prop.props.user.email,
    phone: $prop.props.user.phone,
    requestDomain: 'example.com',
    kodeUnik: $prop.props.domain.kode_unik,
    subTotal: $prop.props.domain.amount*75000,
    totalBayar: $prop.props.domain.total_price,
    status: $prop.props.domain.status // PENDING, PAID, CANCELLED
  });
  
  // Computed properties
  const statusBadgeClass = computed(() => {
    const classes = {
      'pending': 'badge-warning',
      'success': 'badge-success',
      'process': 'badge-primary',
      'failed': 'badge-error'
    };
    return classes[invoice.value.status] || 'badge-ghost';
  });
  
  // Methods
  const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    }).format(price);
  };
  

  
  const handlePayment = () => {
    let message = 'KONFIRMASI PEMBAYARAN REQUEST QUOTA DOMAIN #'+$prop.props.domain.invoice+' | ';
        message+=`INVOICE : ${$prop.props.domain.invoice} | \n`;
        message+=`QUOTA DOMAIN : ${$prop.props.domain.amount} | \n`;
        message+=`TOTAL BAYAR : ${$prop.props.domain.total_price} | \n`;
        message+=`-----------------------------------------\n`;
        message+=`USER EMAIL : ${$prop.props.user.email}\n`;
    window.location.href = `https://wa.me/6285643307507?text=${encodeURIComponent(message)}`;
  };
  </script>
<template>
    <div class="flex h-screen bg-base-200">
      <!-- Sidebar -->
      <aside
        class="fixed inset-y-0 left-0 z-50 w-64 bg-base-100 p-4 shadow-lg transition-transform lg:translate-x-0 lg:block"
        :class="{ '-translate-x-full': !sidebarOpen }"
      >
        <h2 class="text-lg font-bold mb-4">📄 AD-IYH WP PLUGIN</h2>
        <ul class="menu mt-5">
          <li v-for="(m, i) in docs.docs" :key="i">
            <Link :href="'/docs/' + m.slug">👉 {{ m.title }}</Link>
          </li>
        </ul>
      </aside>
  
      <!-- Main Content -->
      <div class="flex-1 flex flex-col transition-all lg:ml-64">
        <!-- Navbar -->
        <header class="bg-base-100 shadow p-4 flex items-center justify-between">
          <button class="btn btn-ghost lg:hidden" @click="sidebarOpen = !sidebarOpen">
            ☰
          </button>
          <h1 class="text-2xl font-bold">AD-IYH</h1>
        </header>
  
        <!-- Page Content -->
        <main class="flex-1 p-5">
            <div class="rounded-lg p-3 md:p-10">
          <slot />
        </div>
        </main>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from "vue";
  import { Link } from "@inertiajs/vue3";
  
  defineProps({ docs: Object });
  const sidebarOpen = ref(false);
  </script>
  
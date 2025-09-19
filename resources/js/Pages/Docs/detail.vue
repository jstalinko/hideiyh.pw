<template>
    <Layout :docs="props">
        <h1 class="text-2xl font-bold underline">📰 {{ props.doc.title }}</h1>

        <div class="mt-5">
            <p class="mt-4 mb-5">Last update : {{ new Date(props.doc.updated_at).toLocaleDateString() }}</p>
            <div class="prose prose-xl" v-html="props.doc.content"></div>
        </div>
    </Layout>
</template>
<script setup>
import Layout from './Layout.vue';
const $prop = defineProps({props:Object});
</script>
<script setup>
import { onMounted } from 'vue';
import { usePagesStore } from '../stores/pages';
import TreeView from '../components/TreeView.vue';

const pagesStore = usePagesStore();

onMounted(async () => {
  await pagesStore.fetchPages();
});
</script>

<template>
  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">CMS Pages</h1>
    
    <div v-if="pagesStore.loading">Loading...</div>
    <div v-else-if="pagesStore.error">Error: {{ pagesStore.error }}</div>
    <div v-else>
      <TreeView :pages="pagesStore.pages" />
    </div>
  </div>
</template>
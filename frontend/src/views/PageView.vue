<script setup>
import { computed, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { usePagesStore } from '../stores/pages';

const route = useRoute();
const router = useRouter();
const pagesStore = usePagesStore();

const currentPage = computed(() => {
  return pagesStore.findPageByPath(route.path);
});

const breadcrumbs = computed(() => {
  if (!currentPage.value) return [];
  
  const segments = route.path.split('/').filter(Boolean);
  const breadcrumbs = [];
  let currentPath = '';
  
  for (const segment of segments) {
    currentPath += `/${segment}`;
    const page = pagesStore.findPageByPath(currentPath);
    if (page) {
      breadcrumbs.push({
        path: currentPath,
        title: page.title
      });
    }
  }
  
  return breadcrumbs;
});

watch(
  () => route.path,
  async () => {
    if (!pagesStore.pages.length) {
      await pagesStore.fetchPages();
    }
  },
  { immediate: true }
);

const navigateToBreadcrumb = (path) => {
  router.push(path);
};
</script>

<template>
  <div class="container mx-auto p-4">
    <div v-if="pagesStore.loading">Loading...</div>
    <div v-else-if="!currentPage">Page not found</div>
    <div v-else>
      <!-- Breadcrumb navigation -->
      <div class="flex items-center space-x-2 text-sm text-gray-600 mb-4">
        <span 
          class="cursor-pointer hover:text-blue-600"
          @click="router.push('/')"
        >
          Home
        </span>
        <template v-for="(crumb, index) in breadcrumbs" :key="crumb.path">
          <span>/</span>
          <span 
            class="cursor-pointer hover:text-blue-600"
            @click="navigateToBreadcrumb(crumb.path)"
          >
            {{ crumb.title }}
          </span>
        </template>
      </div>

      <h1 class="text-3xl font-bold mb-4">{{ currentPage.title }}</h1>
      <div class="prose max-w-none">
        <p class="text-gray-600 mb-4">
          <strong>Slug:</strong> {{ currentPage.slug }}
          <br>
          <strong>ID:</strong> {{ currentPage.id }}
          <br>
          <strong>Parent ID:</strong> {{ currentPage.parent_id || 'Root page' }}
        </p>
        <div class="bg-white p-6 rounded-lg shadow">
          {{ currentPage.content }}
        </div>
      </div>
    </div>
  </div>
</template>
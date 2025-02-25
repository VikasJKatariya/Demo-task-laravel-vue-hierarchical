<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const props = defineProps({
  pages: {
    type: Array,
    required: true
  }
});

const router = useRouter();
const expanded = ref(new Set());

const toggleExpand = (pageId) => {
  if (expanded.value.has(pageId)) {
    expanded.value.delete(pageId);
  } else {
    expanded.value.add(pageId);
  }
};

const navigateToPage = (page) => {
  const buildPath = (page) => {
    const segments = [];
    let current = page;
    
    while (current) {
      segments.unshift(current.slug);
      current = props.pages.find(p => p.id === current.parent_id);
    }
    
    return '/' + segments.join('/');
  };

  router.push(buildPath(page));
};
</script>

<template>
  <ul class="tree-view">
    <li v-for="page in pages" :key="page.id" class="tree-item">
      <div class="flex items-center space-x-2 py-2">
        <button 
          v-if="page.children && page.children.length"
          @click="toggleExpand(page.id)"
          class="w-6 h-6 flex items-center justify-center border rounded"
        >
          {{ expanded.has(page.id) ? '-' : '+' }}
        </button>
        <span 
          @click="navigateToPage(page)"
          class="cursor-pointer hover:text-blue-600"
        >
          {{ page.title }}
        </span>
      </div>
      
      <ul v-if="page.children && expanded.has(page.id)" class="pl-6">
        <TreeView :pages="page.children" />
      </ul>
    </li>
  </ul>
</template>

<style scoped>
.tree-view {
  @apply text-left;
}
.tree-item {
  @apply my-1;
}
</style>
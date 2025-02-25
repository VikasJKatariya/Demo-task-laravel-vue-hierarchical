import { defineStore } from 'pinia';
import axios from 'axios';

export const usePagesStore = defineStore('pages', {
  state: () => ({
    pages: [],
    currentPage: null,
    loading: false,
    error: null
  }),
  
  actions: {
    async fetchPages() {
      this.loading = true;
      try {
        const response = await axios.get('http://127.0.0.1:8000/pages');
        this.pages = response.data;
      } catch (error) {
        this.error = error.message;
      } finally {
        this.loading = false;
      }
    },

    findPageByPath(path) {
      const segments = path.split('/').filter(Boolean);
      let currentPages = this.pages;
      let foundPage = null;

      for (const segment of segments) {
        foundPage = currentPages.find(page => page.slug === segment);
        if (!foundPage) return null;
        currentPages = foundPage.children || [];
      }

      return foundPage;
    }
  }
});
import { defineStore } from 'pinia';
import { ref } from 'vue';
export const snackbarStore = defineStore('snackbarStore', () => {
    const snackbars = ref([]);
    return {
        snackbars
    };
});

import {ref} from 'vue';
import { defineStore } from 'pinia';

export const useStore = defineStore('store', () => {
  const errors = ref([]);
  const setErrors = (do_errors) => {
    errors.value = do_errors;
  }

  return {
    errors,
    setErrors,
  }
});


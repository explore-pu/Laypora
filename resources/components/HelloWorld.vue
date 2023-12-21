<template>
  <div class="mb-4">
    <h1 class="green">{{ msg }}</h1>
  </div>
</template>

<script setup>
defineProps({
  msg: {
    type: String,
    required: true
  }
})
</script>

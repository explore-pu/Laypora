<template>
  <ul class="hidden">
    <template v-for="resource in resources">
      <li class="py-1 truncate" :key="resource.id" @click.stop="checkFolder($event)" @click.right.stop="navRightClick($event, resource)" v-if="resource.type === 0">
        <img class="inline-block pr-1 mb-1" :src="IconRight" alt="direction">
        <img class="inline-block pr-1 mb-1" src="@/assets/folder.svg" alt="folder">
        <span>{{ resource.name }}</span>
        <ComResource :resources="resource.children"
                     @navRightClick="navRightClick"
                     @checkFolder="checkFolder"
                     @checkFile="checkFile">
        </ComResource>
      </li>
      <li class="py-1 truncate" :title="resource.name" :key="resource.id" @click.stop="checkFile($event)" @click.right.stop="navRightClick($event, resource)" v-else>
        <img class="inline-block pr-1 mb-1 pl-1" src="@/assets/file.svg" alt="file">
        <span>{{ resource.name }}</span>
      </li>
    </template>
  </ul>
</template>

<script setup>
import IconRight from "@/assets/right.svg";

defineProps({
  resources: {
    type: Array,
    required: true
  },
});
// 接收父组件事件
const emit = defineEmits([
  'navRightClick',
  'checkFolder',
  'checkFile'
]);
// 定义组件
const navRightClick = (event, resource) => {
  emit('navRightClick', event, resource);
}
// 定义组件
const checkFolder = (event) => {
  emit('checkFolder', event);
}
// 定义组件
const checkFile = (event) => {
  emit('checkFile', event);
}
</script>

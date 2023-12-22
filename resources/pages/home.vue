<template>
  <div class="relative" @click.right.native="$event.preventDefault()">
    <div class="flex">
      <nav class="w-80 h-screen border-r overflow-auto">
        <ul class="px-5 py-2">
          <li class="py-1" @click.right.native="navRightClick($event)">
            <img class="inline-block pr-1" src="@/assets/folder.svg" alt="folder">
            <span>{{ username }}</span>
          </li>
          <li class="py-1 truncate">
            <img class="inline-block pr-1" src="@/assets/right.svg" alt="right">
            <img class="inline-block pr-1" src="@/assets/folder.svg" alt="folder">
            <span>文档</span>
          </li>
          <li class="py-1 truncate">
            <img class="inline-block pr-1" src="@/assets/down.svg" alt="down">
            <img class="inline-block pr-1" src="@/assets/folder.svg" alt="folder">
            <span>目录</span>
            <ul>
              <li class="py-1 truncate">
                <img class="inline-block pr-1 pl-3" src="@/assets/file.svg" alt="file">
                <span>文件</span>
              </li>
              <li class="py-1 bg-gray-200 truncate" title="文件（选中）">
                <img class="inline-block pr-1 pl-3" src="@/assets/file.svg" alt="file">
                <span>文件（选中）</span>
              </li>
              <li class="py-1 truncate" title="文件名很长，文件名很长，文件名很长，文件名很长">
                <img class="inline-block pr-1 pl-3" src="@/assets/file.svg" alt="file">
                <span>文件名很长，文件名很长，文件名很长，文件名很长</span>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <div ref="divRef" class="grow h-screen" />
    </div>
  </div>
</template>

<script setup>
import {onMounted, onUnmounted, ref} from "vue";
import {AiEditor} from "aieditor";
import {test} from "@/axios/requests/test";

const username = localStorage.getItem('name');

const divRef = ref();
let aiEditor = null;

const clicks = () => {
  test().then((res) => {
    console.log(res);
  });
}
const navRightClick = (event) => {
  event.preventDefault();
  console.log('点击了右键');
}

onMounted(() => {
  aiEditor = new AiEditor({
    element: divRef.value,
    toolbarKeys: [],
    placeholder: "",
    content: '',
  })
});

onUnmounted(() => {
  aiEditor && aiEditor.destroy();
});
</script>

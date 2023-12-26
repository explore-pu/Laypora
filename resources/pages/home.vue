<template>
  <div class="relative" @click="RightClickRef.classList.add('hidden')" @click.right.native="$event.preventDefault()">
    <div class="flex">
      <nav class="w-80 h-screen border-r overflow-auto">
        <ul class="px-5 py-2">
          <li class="py-1" @click.right.native="navRightClick($event)">
            <img class="inline-block pr-1" src="@/assets/folder.svg" alt="folder">
            <span>{{ username }}</span>
          </li>
          <template v-for="resource in resources">
            <li class="py-1 truncate" @click="checkFolder($event)" @click.right.native="navRightClick($event)" v-if="resource.type === 0">
              <img class="inline-block pr-1" :src="IconRight" alt="direction">
              <img class="inline-block pr-1" src="@/assets/folder.svg" alt="folder">
              <span>{{ resource.name }}</span>
              <ul class="hidden" v-if="resource.children.length > 0">

                <li class="py-1 truncate bg-gray-200" title="文件（选中）" @click="checkFile($event)">
                  <img class="inline-block pr-1 pl-3" src="@/assets/file.svg" alt="file">
                  <span>文件（选中）</span>
                </li>
                <li class="py-1 truncate" title="文件名很长，文件名很长，文件名很长，文件名很长" @click="checkFile($event)">
                  <img class="inline-block pr-1 pl-3" src="@/assets/file.svg" alt="file">
                  <span>文件名很长，文件名很长，文件名很长，文件名很长</span>
                </li>
              </ul>
            </li>
            <li class="py-1 truncate" :title="resource.name" @click="checkFile($event)" v-else>
              <img class="inline-block pr-1 pl-3" src="@/assets/file.svg" alt="file">
              <span>{{ resource.name }}</span>
            </li>
          </template>
          <li class="py-1 truncate" @click="checkFolder($event)" @click.right.native="navRightClick($event)">
            <img class="inline-block pr-1" :src="IconRight" alt="direction">
            <img class="inline-block pr-1" src="@/assets/folder.svg" alt="folder">
            <span>文档</span>
          </li>
          <li class="py-1 truncate" @click="checkFolder($event)" @click.right.native="navRightClick($event)">
            <img class="inline-block pr-1" :src="IconRight" alt="direction">
            <img class="inline-block pr-1" src="@/assets/folder.svg" alt="folder">
            <span>目录</span>
            <ul class="hidden">
              <li class="py-1 truncate" title="文件" @click="checkFile($event)">
                <img class="inline-block pr-1 pl-3" src="@/assets/file.svg" alt="file">
                <span>文件</span>
              </li>
              <li class="py-1 truncate bg-gray-200" title="文件（选中）" @click="checkFile($event)">
                <img class="inline-block pr-1 pl-3" src="@/assets/file.svg" alt="file">
                <span>文件（选中）</span>
              </li>
              <li class="py-1 truncate" title="文件名很长，文件名很长，文件名很长，文件名很长" @click="checkFile($event)">
                <img class="inline-block pr-1 pl-3" src="@/assets/file.svg" alt="file">
                <span>文件名很长，文件名很长，文件名很长，文件名很长</span>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <div ref="AiEditorRef" class="grow h-screen" />
    </div>
    <div ref="RightClickRef" class="absolute bg-gray-200 hidden">
      <div class="py-1 px-2 cursor-pointer">新建目录</div>
      <div class="py-1 px-2 cursor-pointer">新建文档</div>
      <div class="py-1 px-2 cursor-pointer">重命名</div>
      <div class="py-1 px-2 cursor-pointer">删除</div>
    </div>
  </div>
</template>

<script setup>
import {onMounted, onUnmounted, ref} from "vue";
import {AiEditor} from "aieditor";
import IconRight from "@/assets/right.svg";
import IconDown from "@/assets/down.svg";
import {getResources} from "@/axios/requests/resource.js";

const username = localStorage.getItem('name');

const AiEditorRef = ref();
const RightClickRef = ref();
const resources = ref([]);
let aiEditor = null;

// 导航右键目录事件
const navRightClick = (event) => {
  event.preventDefault();
  console.log('点击了右键', event.currentTarget);

  const e = event || window.event;
  const scrollX = document.documentElement.scrollLeft || document.body.scrollLeft;
  const scrollY = document.documentElement.scrollTop || document.body.scrollTop;
  const x = e.pageX || e.clientX + scrollX;
  const y = e.pageY || e.clientY + scrollY;
  RightClickRef.value.style.top = `${y}px`;
  RightClickRef.value.style.left = `${x}px`;
  RightClickRef.value.classList.remove('hidden');
}

// 导航点击目录事件
const checkFolder = (event) => {
  // 最后一个子元素
  const lastChildDom = event.currentTarget.lastChild;
  // 获取标签名称
  const lastTagName = lastChildDom.tagName;
  // 如果是UL标签
  if (lastTagName === 'UL') {
    lastChildDom.classList.toggle('hidden');
    const isHidden = lastChildDom.classList.contains('hidden');
    event.currentTarget.firstChild.src = isHidden ? IconRight : IconDown;
  }
}

// 导航点击文件事件
const checkFile = (event) => {
  console.log('点击了文件', event.currentTarget);
}

onMounted(() => {
  aiEditor = new AiEditor({
    element: AiEditorRef.value,
    toolbarKeys: [],
    placeholder: "",
    content: '',
  });

  getResources().then((res) => {
    resources.value = res;
    console.log(res);
  });
});

onUnmounted(() => {
  aiEditor && aiEditor.destroy();
});
</script>

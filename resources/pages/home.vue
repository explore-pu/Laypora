<template>
  <div class="relative" @click="allClick($event)" @click.right="$event.preventDefault()">
    <div class="flex">
      <nav class="w-80 h-screen border-r overflow-auto">
        <ul class="px-5 py-2">
          <li class="py-1" @click.right="navRightClick($event)">
            <img class="inline-block pr-1 mb-1" src="@/assets/folder.svg" alt="folder">
            <span>{{ nickname }}</span>
            <ul class="ps-0">
              <template v-for="resource in resources">
                <li class="py-1 truncate" :key="resource.id" @click="checkFolder($event)" @click.right.stop="navRightClick($event, resource)" v-if="resource.type === 0">
                  <img class="inline-block pr-1 mb-1" :src="IconRight" alt="direction">
                  <img class="inline-block pr-1 mb-1" src="@/assets/folder.svg" alt="folder">
                  <span>{{ resource.name }}</span>
                  <ComResource :resources="resource.children"
                               @navRightClick="navRightClick"
                               @checkFolder="checkFolder"
                               @checkFile="checkFile">
                  </ComResource>
                </li>
                <li class="py-1 truncate" :title="resource.name" :key="resource.id" @click="checkFile($event)" @click.right.stop="navRightClick($event, resource)" v-else>
                  <img class="inline-block pr-1 mb-1 pl-1" src="@/assets/file.svg" alt="file">
                  <span>{{ resource.name }}</span>
                </li>
              </template>
            </ul>
          </li>
        </ul>
      </nav>
      <div ref="AiEditorRef" class="grow h-screen" />
    </div>
    <div ref="RightClickRef" class="absolute bg-gray-200 hidden">
      <div class="py-1 px-2 cursor-pointer" v-if="RightClickResource === null || RightClickResource.type === 0" @click="create(0)">新建目录</div>
      <div class="py-1 px-2 cursor-pointer" v-if="RightClickResource === null || RightClickResource.type === 0" @click="create(1)">新建文档</div>
      <div class="py-1 px-2 cursor-pointer" v-if="RightClickResource !== null">重命名</div>
      <div class="py-1 px-2 cursor-pointer" v-if="RightClickResource !== null && RightClickResource.type === 1">下载</div>
      <div class="py-1 px-2 cursor-pointer" v-if="RightClickResource !== null">删除</div>
    </div>

    <div ref="CreateFormRef" class="absolute top-0 bottom-0 left-0 right-0 backdrop-grayscale-0 bg-gray-500/20 flex items-center justify-center hidden">
      <div class="py-4 px-4 bg-white rounded-md flex gap-4">
        <input ref="InputRef" type="text" placeholder="请输入 名称" class="block rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6" />
        <button type="button" class="px-2 border rounded bg-green-500" @click="confirmCreation">确认</button>
        <button type="button" class="px-2 border rounded bg-gray-400" @click="cancelCreation">取消</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import {onMounted, onUnmounted, reactive, ref} from "vue";
import {AiEditor} from "aieditor";
import IconRight from "@/assets/right.svg";
import IconDown from "@/assets/down.svg";
import {apiResources, apiStoreResource} from "@/axios/requests/resource.js";
import ComResource from "@/components/com-resource.vue";
// 登录用户的昵称
const nickname = localStorage.getItem('name');
// 编辑器DOM
const AiEditorRef = ref();
// 编辑器对象
let aiEditor = null;
// 右键DOM
const RightClickRef = ref();
// 创建表单DOM
const CreateFormRef = ref();
// 表单DOM
const InputRef = ref();
// 资源数据
const resources = ref([]);
// 右键资源DOM
const RightClickResourceDom = ref(null);
// 右键资源数据
const RightClickResource = ref(null);
// 创建数据
const createData = reactive({
  name: '',
  type: 0,
  parent_id: 0,
});
// 全局点击
const allClick = (event) => {
  RightClickRef.value.classList.add('hidden');
}
// 导航右键事件
const navRightClick = (event, resource = null) => {
  event.preventDefault();
  console.log('点击了右键', event.target.parentNode);
  console.log('resource', resource);
  RightClickResourceDom.value = event.target.parentNode;
  RightClickResource.value = resource;
  RightClickRef.value.style.top = `${event.pageY}px`;
  RightClickRef.value.style.left = `${event.pageX}px`;
  RightClickRef.value.classList.remove('hidden');
}
// 导航点击目录事件
const checkFolder = (event) => {
  console.log('点击了目录', event.target.parentNode);
  const currentTarget = event.target.parentNode;
  // 最后一个子元素
  const lastChildDom = currentTarget.lastChild;
  // 如果是UL标签
  if (lastChildDom.tagName === 'UL') {
    lastChildDom.classList.toggle('hidden');
    const isHidden = lastChildDom.classList.contains('hidden');
    currentTarget.firstChild.src = isHidden ? IconRight : IconDown;
    if (isHidden) {
      const ulList = currentTarget.querySelectorAll('ul');
      ulList.forEach(ul => {
        ul.classList.add('hidden');
        ul.parentNode.firstChild.src = IconRight;
      });
    }
  }
}
// 导航点击文件事件
const checkFile = (event) => {
  console.log('点击了文件', event.target.parentNode);
}
// 新建
const create = (type) => {
  InputRef.value.value = '';
  createData.type = type;
  createData.parent_id = RightClickResource.value !== null ? RightClickResource.value.id : 0;
  CreateFormRef.value.classList.remove('hidden');
}
const confirmCreation = () => {
  createData.name = InputRef.value.value;

  apiStoreResource(createData).then(res => {
    createData.id = res;
    createData.children = [];
    if (RightClickResource.value === null) {
      resources.value.unshift(createData);
    } else {
      RightClickResource.value.children.unshift(createData);
    }

    CreateFormRef.value.classList.add('hidden');
    RightClickResourceDom.value.lastChild.classList.remove('hidden');
    console.log(res);
  }).catch(error => {
    console.log(error);
  })

  // CreateFormRef.value.classList.add('hidden');
  // console.log('createData', createData);
}
const cancelCreation = () => {
  CreateFormRef.value.classList.add('hidden');
}
// onMounted生命周期
onMounted(() => {
  aiEditor = new AiEditor({
    element: AiEditorRef.value,
    toolbarKeys: [],
    placeholder: "",
    content: '',
  });
  // 请求资源接口
  apiResources().then((res) => {
    resources.value = res;
    console.log(res);
  });
});
// onUnmounted生命周期
onUnmounted(() => {
  aiEditor && aiEditor.destroy();
});
</script>

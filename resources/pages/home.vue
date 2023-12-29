<template>
  <div class="relative" @click="RightClickRef.value.classList.add('hidden')" @click.right="$event.preventDefault()">
    <div class="flex">
      <nav class="w-80 h-screen border-r overflow-auto">
        <ul class="px-5 py-2">
          <li class="py-1" @click.right="navRightClick($event)">
            <img class="inline-block pr-1 mb-1" src="@/assets/house.svg" alt="folder">
            <span>{{ nickname }}</span>
            <ul class="ps-0">
              <template v-for="resource in resources">
                <li class="py-1 truncate" :key="resource.id" @click="checkFolder($event)"
                    @click.right.stop="navRightClick($event, resource)" v-if="resource.type === 0">
                  <img class="inline-block pr-1 mb-1" :src="IconRight" alt="direction">
                  <img class="inline-block pr-1 mb-1" src="@/assets/folder.svg" alt="folder">
                  <span>{{ resource.name }}</span>
                  <ComResource :resources="resource.children"
                               @navRightClick="navRightClick"
                               @checkFolder="checkFolder"
                               @checkFile="checkFile">
                  </ComResource>
                </li>
                <li class="py-1 truncate" :title="resource.name" :key="resource.id"
                    @click="checkFile($event, resource.id)" @click.right.stop="navRightClick($event, resource)" v-else>
                  <img class="inline-block pr-1 mb-1 pl-1" src="@/assets/file.svg" alt="file">
                  <span>{{ resource.name }}.md</span>
                </li>
              </template>
            </ul>
          </li>
        </ul>
      </nav>
      <div ref="AiEditorRef" class="grow h-screen"/>
    </div>

    <div ref="RightClickRef" class="absolute bg-gray-200 hidden">
      <div class="py-1 px-2 cursor-pointer" v-if="RightClickResource === null || RightClickResource.type === 0" @click="create(0)">新建目录</div>
      <div class="py-1 px-2 cursor-pointer" v-if="RightClickResource === null || RightClickResource.type === 0" @click="create(1)">新建文档</div>
      <div class="py-1 px-2 cursor-pointer" v-if="RightClickResource !== null" @click="rename">重命名</div>
      <div class="py-1 px-2 cursor-pointer" v-if="RightClickResource !== null && RightClickResource.type === 1">下载</div>
      <div class="py-1 px-2 cursor-pointer" v-if="RightClickResource !== null" @click="destroy">删除</div>
    </div>

    <div ref="formRef" class="form hidden">
      <div class="py-4 px-4 bg-white rounded-md flex gap-4">
        <input ref="InputRef" type="text" placeholder="请输入 名称" class="block rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"/>
        <button type="button" class="px-2 border rounded bg-green-500" @click="confirmForm">确认</button>
        <button type="button" class="px-2 border rounded bg-gray-400" @click="cancelForm">取消</button>
      </div>
    </div>

    <div ref="messageRef" class="message hidden"></div>
  </div>
</template>

<script setup>
import {onMounted, onUnmounted, reactive, ref} from "vue";
import {AiEditor} from "aieditor";
import IconRight from "@/assets/right.svg";
import IconDown from "@/assets/down.svg";
import {apiResources, apiStoreResource, apiRenameResource, apiDestroyResource} from "@/axios/requests/resource.js";
import {apiShowMarkdown, apiSaveMarkdown} from "@/axios/requests/markdown.js";
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
const formRef = ref();
// 表单DOM
const InputRef = ref();
// 消息DOM
const messageRef = ref();
// 资源数据
const resources = ref([]);
// 右键资源DOM
const RightClickResourceDom = ref(null);
// 右键资源数据
const RightClickResource = ref(null);
// 正在编辑的资源ID
const editingId = ref(null);
// 创建数据
const createData = reactive({
  name: '',
  type: 0,
  parent_id: 0,
});
// 导航右键事件
const navRightClick = (event, resource = null) => {
  event.preventDefault();
  // console.log('点击了右键', event.target.parentNode);
  RightClickResourceDom.value = event.target.parentNode;
  RightClickResource.value = resource;
  RightClickRef.value.style.top = `${event.pageY}px`;
  RightClickRef.value.style.left = `${event.pageX}px`;
  RightClickRef.value.classList.remove('hidden');
}
// 导航点击目录事件
const checkFolder = (event) => {
  // console.log('点击了目录', event.target.parentNode);
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
const checkFile = (event, id) => {
  // console.log('点击了文件', event.target.parentNode);
  editingId.value = id;
  if (aiEditor === null) {
    enAiEditor();
  }
  aiEditor.clear();
  apiShowMarkdown(id).then(res => {
    aiEditor.insert(res);
  }).catch(error => {
    message(error.data.msg);
  });
}
// 新建资源按钮
const create = (type) => {
  InputRef.value.value = '';
  createData.type = type;
  createData.parent_id = RightClickResource.value !== null ? RightClickResource.value.id : 0;
  formRef.value.dataset.action = 'create';
  formRef.value.classList.remove('hidden');
}
// 重命名资源按钮
const rename = () => {
  InputRef.value.value = RightClickResource.value.name;
  formRef.value.dataset.action = 'rename';
  formRef.value.classList.remove('hidden');
}
// 删除资源按钮
const destroy = () => {
  apiDestroyResource(RightClickResource.value.id).then(res => {
    message(res.msg);
    RightClickResourceDom.value.remove();
    // 如果删除的是文档
    if (RightClickResource.value.type === 1) {
      aiEditor.destroy();
      aiEditor = null;
    }
  }).catch(error => {
    message(error.data.msg);
  });
}
// 表单确认按钮
const confirmForm = () => {
  createData.name = InputRef.value.value;
  if (formRef.value.dataset.action === 'create') {
    storeResource();
  }
  if (formRef.value.dataset.action === 'rename') {
    renameResource();
  }
}
// 创建确认
const storeResource = () => {
  apiStoreResource(createData).then(res => {
    const newResource = {
      id: res,
      name: createData.name,
      type: createData.type,
      parent_id: createData.parent_id,
      children: [],
    }

    if (RightClickResource.value === null) {
      resources.value.unshift(newResource);
    } else {
      RightClickResource.value.children.unshift(newResource);
    }

    formRef.value.classList.add('hidden');
    RightClickResourceDom.value.lastChild.classList.remove('hidden');
  }).catch(error => {
    message(error.data.msg);
  });
}
// 重命名确认
const renameResource = () => {
  apiRenameResource(RightClickResource.value.id, {name: InputRef.value.value}).then(res => {
    message(res.msg);
    RightClickResource.value.name = InputRef.value.value;
    formRef.value.classList.add('hidden');
  }).catch(error => {
    message(error.data.msg);
  });
}
// 表单取消按钮
const cancelForm = () => {
  formRef.value.classList.add('hidden');
}
// 启用编辑器
const enAiEditor = () => {
  aiEditor = new AiEditor({
    element: AiEditorRef.value,
    toolbarKeys: [],
    placeholder: "点击输入内容...，Ctrl + S 保存",
    content: '',
    onChange: (aiEditor) => {
      // 监听到用编辑器内容发生变化了，控制台打印编辑器的 html 内容...
      // console.log(aiEditor.getOutline())
    }
  });
}
const message = (msg) => {
  messageRef.value.innerText = msg;
  messageRef.value.classList.remove('hidden');
  const time = setTimeout(() => {
    messageRef.value.classList.add('hidden');
    messageRef.value.innerText = '';
    clearTimeout(time);
  }, 3000);
}
// onMounted生命周期
onMounted(() => {
  // 注册事件
  window.addEventListener("keydown", function (event) {
    //可以判断是不是mac，如果是mac,ctrl变为花键
    //event.preventDefault() 方法阻止元素发生默认的行为。
    if (event.keyCode === 83 && (navigator.platform.match("Mac") ? event.metaKey : event.ctrlKey)) {
      event.preventDefault();
      apiSaveMarkdown(editingId.value, {content: aiEditor.getMarkdown()}).then(res => {
        message(res.msg);
      }).catch(error => {
        message(error.data.msg);
      });
    }
  }, false);

  // 请求资源接口
  apiResources().then(res => {
    resources.value = res;
  });
});
// onUnmounted生命周期
onUnmounted(() => {
  aiEditor && aiEditor.destroy();
});
</script>

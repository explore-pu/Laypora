<template>
  <div class="flex">
    <h1>AiEditor，一个面向 AI 的富文本编辑器</h1>
    <div ref="divRef" style="height: 600px"/>
  </div>
</template>

<script setup>
import {test} from "@/axios/requests/test";
import {AiEditor} from "aieditor";
import "aieditor/dist/style.css"
import {onMounted, onUnmounted, ref} from "vue";

const divRef = ref();
let aiEditor = null;

const clicks = () => {
  test().then((res) => {
    console.log(res);
  });
}

onMounted(() => {
  aiEditor = new AiEditor({
    element: divRef.value,
    placeholder: "点击输入内容...",
    content: 'AiEditor 是一个面向 AI 的开源富文本编辑器。 ',
  })
})

onUnmounted(() => {
  aiEditor && aiEditor.destroy();
})
</script>
import { fileURLToPath, URL } from 'node:url';

import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./resources', import.meta.url))
    }
  },
  envPrefix: ['APP_'],
  publicDir: 'resources/public',
  build: {
    // 解决打包兼容性
    target: 'chrome89',
    outDir: 'public',
    // 生产环境移除console
    minify: 'terser',
    terserOptions: {
      compress: {
        drop_console: true,
      },
    },
  }
});

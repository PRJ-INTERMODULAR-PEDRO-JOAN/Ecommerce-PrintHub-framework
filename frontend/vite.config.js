import { fileURLToPath, URL } from 'node:url'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  server: {
    port: 5173,
    proxy: {
      '/api': {
        target: 'http://localhost', // SIN PUERTO (implica 80)
        changeOrigin: true,
        headers: {
          Accept: 'application/json',
          "X-Requested-With": "XMLHttpRequest"
        }
      },
      '/sanctum': {
        target: 'http://localhost', // SIN PUERTO
        changeOrigin: true,
      }
    }
  },
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  }
})
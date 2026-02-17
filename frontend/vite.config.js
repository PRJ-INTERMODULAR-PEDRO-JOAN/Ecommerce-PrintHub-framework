import { fileURLToPath, URL } from 'node:url'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  server: {
    port: 5173, // Vite intentará este puerto, si está ocupado usará el 5174 (es normal)
    proxy: {
      '/api': {
        target: 'http://localhost', // O http://127.0.0.1 si también falla Laravel
        changeOrigin: true,
        headers: {
          Accept: 'application/json',
          "X-Requested-With": "XMLHttpRequest"
        }
      },
      // --- PROXY PARA N8N (Corrección Error 500) ---
      '/n8n': {
        target: 'http://localhost:5678', // USA LA IP 127.0.0.1 EN LUGAR DE LOCALHOST
        changeOrigin: true,
        secure: false, // Importante si n8n no tiene HTTPS configurado
        rewrite: (path) => path.replace(/^\/n8n/, '')
      }
    }
  },
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  }
})
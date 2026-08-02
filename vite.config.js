import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [react()],
  base: './',
  build: {
    outDir: 'bridge-plugin/dist',
    emptyOutDir: true,
    sourcemap: true,
    chunkSizeWarningLimit: 500,
    rollupOptions: {
      output: {
        manualChunks(id) {
          if (!id.includes('node_modules')) return;
          if (id.includes('recharts') || id.includes('/d3-')) return 'charts';
          if (id.includes('quill')) return 'editor';
          if (id.includes('dnd-kit')) return 'dnd';
          if (id.includes('jszip')) return 'utils';
          if (id.includes('lucide-react')) return 'icons';
          if (id.includes('/react/') || id.includes('/react-dom/') || id.includes('/scheduler/')) return 'vendor-react';
          return 'vendor';
        }
      }
    }
  },
})


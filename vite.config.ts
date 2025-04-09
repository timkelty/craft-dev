import { vitePluginCraftCms } from "vite-plugin-craftcms";
import viteRestart from "vite-plugin-restart";
import { defineConfig, loadEnv } from "vite";

// https://vitejs.dev/config/
export default defineConfig(({ command, mode }) => {
  const env = loadEnv(mode, process.cwd(), '');

  return {
    base: command === "serve" ? "" : `${env.CRAFT_CLOUD_ARTIFACT_BASE_URL || ''}/dist/`,
    publicDir: "./web/dist",
    server: {
      port: env || 3000,
    },
    build: {
      emptyOutDir: true,
      manifest: true,
      outDir: "./web/dist/",
      rollupOptions: {
        input: "./src/entry.html",
      },
    },
    plugins: [
      vitePluginCraftCms({
        outputFile: "./templates/_partials/vite.twig",
      }),
      viteRestart({
        reload: ["./templates/**/*"],
      }),
    ],
  };
});

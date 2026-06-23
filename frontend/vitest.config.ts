import { defineConfig } from "vitest/config";

export default defineConfig({
  resolve: {
    tsconfigPaths: true,
  },
  test: {
    globals: true,
    environment: "jsdom",
    include: ["app/**/*.{test,spec}.{ts,tsx}"],
    setupFiles: "./app/test/setup.ts",
  },
});
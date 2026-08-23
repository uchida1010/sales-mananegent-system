import { defineConfig } from "oxlint";

export default defineConfig({
  env: { browser: true, node: true },
  plugins: ["eslint", "typescript", "unicorn", "oxc", "react", "vitest", "jsx-a11y"],
  settings: { react: { version: "19.2.3" } },
  ignorePatterns: ["build/**", ".react-router/**", "app/api/**", "coverage/**"],
});

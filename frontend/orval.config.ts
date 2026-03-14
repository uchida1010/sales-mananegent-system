import { defineConfig } from 'orval';

export default defineConfig({
  aaa: {
    input: '../backend/api.json',
    output: {
      target: './app/api/salesManagementSystem.ts',
      override: {
        mutator: {
          path: './app/api/custom-instance.ts',
          name: 'customInstance',
        },
      },
    },
  },
});
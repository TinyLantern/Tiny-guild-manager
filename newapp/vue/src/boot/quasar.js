// src/boot/quasar.js
import { Quasar } from 'quasar';

export default async ({ app }) => {
  app.use(Quasar, {
    config: {
      // Quasar config options
    },
  });
};
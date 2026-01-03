<script setup lang="ts">
import { RouterLink } from "vue-router";
</script>

<template>
  <aside class="w-64 bg-white border-r p-4">
    <nav class="space-y-1">
      <RouterLink
        to="/"
        class="block rounded px-3 py-2 text-sm hover:bg-gray-100"
        active-class="bg-gray-100 font-medium"
      >
        Dashboard
      </RouterLink>

      <RouterLink
        to="/tickets"
        class="block rounded px-3 py-2 text-sm hover:bg-gray-100"
        active-class="bg-gray-100 font-medium"
      >
        Tickets
      </RouterLink>
    </nav>
  </aside>
</template>

<script setup lang="ts">
import AppNavbar from "./AppNavbar.vue";
import AppSidebar from "./AppSidebar.vue";
</script>

<template>
  <div class="flex h-screen bg-gray-100">
    <AppSidebar />
    <div class="flex flex-col flex-1">
      <AppNavbar />
      <main class="p-6 overflow-y-auto">
        <slot />
      </main>
    </div>
  </div>
</template>

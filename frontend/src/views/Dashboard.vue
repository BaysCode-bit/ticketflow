<script setup lang="ts">
import AppLayout from "@/components/layout/AppLayout.vue";

const stats = [
  { label: "Open Tickets", value: 12 },
  { label: "In Progress", value: 5 },
  { label: "Closed Today", value: 8 },
];
</script>

<template>
  <AppLayout>
    <h2 class="text-2xl font-semibold mb-6">Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div
        v-for="stat in stats"
        :key="stat.label"
        class="bg-white rounded border p-4"
      >
        <p class="text-sm text-gray-500">{{ stat.label }}</p>
        <p class="text-2xl font-bold">{{ stat.value }}</p>
      </div>
    </div>
  </AppLayout>
</template>

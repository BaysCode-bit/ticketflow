<script setup lang="ts">
import { onMounted, ref } from "vue";
import api from "@/services/api";

const tickets = ref<any[]>([]);
const loading = ref(true);

onMounted(async () => {
  const res = await api.get("/tickets");
  tickets.value = res.data.filter((t: any) => t.assigned_agent_id !== null);
  loading.value = false;
});
</script>

<template>
  <div class="min-h-screen bg-slate-100 px-6 py-8">
    <div class="max-w-6xl mx-auto">
      <h1 class="text-2xl font-semibold text-slate-900 mb-6">
        Agent Dashboard
      </h1>

      <div v-if="loading" class="text-slate-500 text-sm">
        Loading tickets...
      </div>

      <div v-else class="grid grid-cols-1 gap-4">
        <div
          v-for="ticket in tickets"
          :key="ticket.id"
          class="rounded-xl bg-white border border-slate-200 p-4"
        >
          <h2 class="font-medium text-slate-900">
            {{ ticket.subject }}
          </h2>
          <p class="text-sm text-slate-500">Category: {{ ticket.category }}</p>
          <p class="text-xs text-slate-400">Status: {{ ticket.status }}</p>
        </div>

        <div v-if="tickets.length === 0" class="text-sm text-slate-500">
          No tickets assigned to you yet.
        </div>
      </div>
    </div>
  </div>
</template>

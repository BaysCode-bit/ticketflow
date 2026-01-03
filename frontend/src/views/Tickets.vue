<script setup lang="ts">
import { onMounted, ref } from "vue";
import api from "@/services/api";
import AppLayout from "@/components/layout/AppLayout.vue";
import { RouterLink } from "vue-router";

interface Ticket {
  id: number;
  subject: string;
  category: string;
  status: string;
}

const tickets = ref<Ticket[]>([]);
const loading = ref(true);

onMounted(async () => {
  const res = await api.get("/tickets");
  tickets.value = res.data;
  loading.value = false;
});

function statusClass(status: string) {
  if (status === "open") return "bg-yellow-100 text-yellow-800";
  if (status === "in_progress") return "bg-blue-100 text-blue-800";
  if (status === "closed") return "bg-green-100 text-green-800";
  return "";
}
</script>

<template>
  <AppLayout>
    <h2 class="text-2xl font-semibold mb-6">Tickets</h2>

    <div v-if="loading">Loading...</div>

    <div v-else class="bg-white rounded border overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3">Subject</th>
            <th class="px-4 py-3">Category</th>
            <th class="px-4 py-3">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="ticket in tickets" :key="ticket.id" class="border-t">
            <td class="px-4 py-3">
              <RouterLink
                :to="`/tickets/${ticket.id}`"
                class="text-blue-600 hover:underline"
              >
                {{ ticket.subject }}
              </RouterLink>
            </td>
            <td class="px-4 py-3">{{ ticket.category }}</td>
            <td class="px-4 py-3">
              <span
                class="px-2 py-1 rounded text-xs"
                :class="statusClass(ticket.status)"
              >
                {{ ticket.status }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AppLayout>
</template>

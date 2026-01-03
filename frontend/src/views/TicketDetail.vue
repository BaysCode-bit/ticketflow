<script setup lang="ts">
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import api from "../services/api";
import AppLayout from "../components/layout/AppLayout.vue";
import { useAuthStore } from "../stores/auth";

interface Ticket {
  id: number;
  subject: string;
  status: string;
}

interface Message {
  id: number;
  sender_type: string;
  message: string;
  created_at: string;
}

const route = useRoute();
const auth = useAuthStore();

const ticket = ref<Ticket | null>(null);
const messages = ref<Message[]>([]);
const reply = ref("");
const loading = ref(true);
const actionLoading = ref(false);

async function fetchTicket() {
  const res = await api.get(`/tickets/${route.params.id}`);
  ticket.value = res.data.ticket;
  messages.value = res.data.messages;
}

async function sendReply() {
  if (!reply.value || !ticket.value) return;

  actionLoading.value = true;
  await api.post(`/tickets/${ticket.value.id}/reply`, {
    message: reply.value,
  });
  reply.value = "";
  await fetchTicket();
  actionLoading.value = false;
}

async function closeTicket() {
  if (!ticket.value) return;

  actionLoading.value = true;
  await api.post(`/admin/tickets/${ticket.value.id}/close`);
  await fetchTicket();
  actionLoading.value = false;
}

onMounted(async () => {
  await fetchTicket();
  loading.value = false;
});
</script>

<template>
  <AppLayout>
    <div v-if="loading">Loading...</div>

    <div v-else-if="ticket">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-semibold">
          {{ ticket.subject }}
        </h2>

        <button
          v-if="auth.user?.role === 'admin' && ticket.status !== 'closed'"
          class="bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700 disabled:opacity-50"
          :disabled="actionLoading"
          @click="closeTicket"
        >
          Close Ticket
        </button>
      </div>

      <div class="mb-4">
        <span
          class="px-2 py-1 rounded text-xs font-medium"
          :class="{
            'bg-yellow-100 text-yellow-800': ticket.status === 'open',
            'bg-blue-100 text-blue-800': ticket.status === 'in_progress',
            'bg-green-100 text-green-800': ticket.status === 'closed',
          }"
        >
          {{ ticket.status }}
        </span>
      </div>

      <div class="bg-white border rounded p-4 mb-6">
        <div
          v-for="msg in messages"
          :key="msg.id"
          class="mb-3 border-b pb-2 last:border-b-0"
        >
          <div class="text-xs text-gray-500 mb-1">
            {{ msg.sender_type }} • {{ msg.created_at }}
          </div>
          <p class="text-sm">{{ msg.message }}</p>
        </div>
      </div>

      <div
        v-if="auth.user?.role === 'agent' && ticket.status !== 'closed'"
        class="bg-white border rounded p-4"
      >
        <textarea
          v-model="reply"
          rows="4"
          class="w-full border rounded p-2 text-sm"
          placeholder="Type your reply..."
        />
        <div class="mt-3 text-right">
          <button
            class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 disabled:opacity-50"
            :disabled="actionLoading"
            @click="sendReply"
          >
            Send Reply
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

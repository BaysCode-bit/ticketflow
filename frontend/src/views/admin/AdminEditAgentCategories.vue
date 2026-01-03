<script setup lang="ts">
import { onMounted, ref } from "vue";
import api from "@/services/api";

type Category =
  | "shipping"
  | "payment"
  | "account"
  | "product"
  | "return_refund";

const agents = ref<any[]>([]);
const loading = ref(true);

async function fetchAgents() {
  const res = await api.get("/users");
  agents.value = res.data.filter((u: any) => u.role === "agent");
  loading.value = false;
}

async function updateCategories(agentId: number, categories: Category[]) {
  await api.put(`/admin/agents/${agentId}/categories`, {
    categories,
  });
  alert("Categories updated");
}

onMounted(fetchAgents);
</script>

<template>
  <div class="min-h-screen bg-slate-100 px-6 py-8">
    <div class="max-w-6xl mx-auto">
      <h1 class="text-2xl font-semibold text-slate-900 mb-6">
        Edit Agent Categories
      </h1>

      <div v-if="loading" class="text-sm text-slate-500">Loading agents...</div>

      <div v-else class="space-y-4">
        <div
          v-for="agent in agents"
          :key="agent.id"
          class="rounded-xl bg-white border border-slate-200 p-4"
        >
          <h2 class="font-medium text-slate-900 mb-2">
            {{ agent.name }} ({{ agent.email }})
          </h2>

          <div class="grid grid-cols-2 gap-2 text-sm">
            <label
              ><input
                type="checkbox"
                value="shipping"
                v-model="agent.categories"
              />
              Shipping</label
            >
            <label
              ><input
                type="checkbox"
                value="payment"
                v-model="agent.categories"
              />
              Payment</label
            >
            <label
              ><input
                type="checkbox"
                value="account"
                v-model="agent.categories"
              />
              Account</label
            >
            <label
              ><input
                type="checkbox"
                value="product"
                v-model="agent.categories"
              />
              Product</label
            >
            <label
              ><input
                type="checkbox"
                value="return_refund"
                v-model="agent.categories"
              />
              Return & Refund</label
            >
          </div>

          <button
            class="mt-4 rounded-lg bg-black px-4 py-2 text-sm text-white hover:bg-slate-800"
            @click="updateCategories(agent.id, agent.categories)"
          >
            Save Changes
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

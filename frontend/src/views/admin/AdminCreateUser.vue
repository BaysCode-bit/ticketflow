<script setup lang="ts">
import { ref, watch } from "vue";
import api from "@/services/api";
import { useRouter } from "vue-router";

type Role = "admin" | "agent";
type Category =
  | "shipping"
  | "payment"
  | "account"
  | "product"
  | "return_refund";

const router = useRouter();

const name = ref("");
const email = ref("");
const password = ref("");
const role = ref<Role>("agent");
const categories = ref<Category[]>([]);

const loading = ref(false);
const error = ref<string | null>(null);
const success = ref(false);

watch(role, (newRole) => {
  if (newRole !== "agent") {
    categories.value = [];
  }
});

async function submitForm() {
  error.value = null;
  loading.value = true;

  try {
    await api.post("/admin/users", {
      name: name.value,
      email: email.value,
      password: password.value,
      role: role.value,
      categories: role.value === "agent" ? categories.value : undefined,
    });

    success.value = true;
    name.value = "";
    email.value = "";
    password.value = "";
    role.value = "agent";
    categories.value = [];
  } catch (err: any) {
    error.value =
      err.response?.data?.message ||
      "Failed to create user. Please check the input.";
  } finally {
    loading.value = false;
  }
}

function goBack() {
  router.push("/");
}
</script>

<template>
  <div class="min-h-screen bg-slate-100 flex items-center justify-center px-4">
    <div
      class="w-full max-w-lg bg-white rounded-2xl border border-slate-200 shadow-sm p-8"
    >
      <h1 class="text-2xl font-semibold text-slate-900 mb-1">
        Create New User
      </h1>
      <p class="text-sm text-slate-500 mb-6">
        Create a new admin or support agent account.
      </p>

      <div
        v-if="success"
        class="rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-700 mb-4"
      >
        User created successfully.
      </div>

      <div
        v-if="error"
        class="rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-600 mb-4"
      >
        {{ error }}
      </div>

      <form class="space-y-5" @submit.prevent="submitForm">
        <!-- NAME -->
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">
            Name
          </label>
          <input
            v-model="name"
            type="text"
            required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">
            Email
          </label>
          <input
            v-model="email"
            type="email"
            required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">
            Password
          </label>
          <input
            v-model="password"
            type="password"
            required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">
            Role
          </label>
          <select
            v-model="role"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900"
          >
            <option value="agent">Agent</option>
            <option value="admin">Admin</option>
          </select>
        </div>

        <div v-if="role === 'agent'">
          <label class="block text-sm font-medium text-slate-700 mb-2">
            Ticket Categories
          </label>

          <div class="grid grid-cols-2 gap-2 text-sm">
            <label class="flex items-center gap-2">
              <input type="checkbox" value="shipping" v-model="categories" />
              Shipping
            </label>

            <label class="flex items-center gap-2">
              <input type="checkbox" value="payment" v-model="categories" />
              Payment
            </label>

            <label class="flex items-center gap-2">
              <input type="checkbox" value="account" v-model="categories" />
              Account
            </label>

            <label class="flex items-center gap-2">
              <input type="checkbox" value="product" v-model="categories" />
              Product
            </label>

            <label class="flex items-center gap-2">
              <input
                type="checkbox"
                value="return_refund"
                v-model="categories"
              />
              Return & Refund
            </label>
          </div>
        </div>

        <div class="flex gap-3 pt-2">
          <button
            type="submit"
            :disabled="loading"
            class="flex-1 rounded-lg bg-black py-2.5 text-sm font-semibold text-white hover:bg-slate-800 disabled:opacity-50"
          >
            {{ loading ? "Creating..." : "Create User" }}
          </button>

          <button
            type="button"
            class="flex-1 rounded-lg border border-slate-300 py-2.5 text-sm hover:bg-slate-100"
            @click="goBack"
          >
            Cancel
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

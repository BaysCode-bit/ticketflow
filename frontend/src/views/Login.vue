<script setup lang="ts">
import { ref } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";

const router = useRouter();
const auth = useAuthStore();

type Mode = "login" | "guest";
const mode = ref<Mode>("login");

const email = ref("");
const password = ref("");
const loading = ref(false);
const error = ref<string | null>(null);

async function submitLogin() {
  error.value = null;
  loading.value = true;

  try {
    await auth.login(email.value, password.value);

    if (auth.user?.role === "admin") {
      router.push("/admin");
    } else if (auth.user?.role === "agent") {
      router.push("/agent");
    } else {
      router.push("/");
    }
  } catch (e: any) {
    error.value = "Invalid email or password";
  } finally {
    loading.value = false;
  }
}

function continueAsGuest() {
  router.push("/guest/tickets");
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-slate-100 px-4">
    <div
      class="w-full max-w-md rounded-2xl bg-white p-8 shadow-sm border border-slate-200"
    >
      <div class="flex flex-col items-center mb-6">
        <img src="/logo.svg" alt="TicketFlow" class="h-12 mb-3" />
        <h1 class="text-2xl font-bold text-slate-900">TicketFlow</h1>
        <p class="text-slate-600 text-sm">Customer Support Ticketing System</p>
      </div>

      <div class="flex rounded-lg bg-slate-100 p-1 mb-6">
        <button
          class="flex-1 py-2 text-sm font-medium rounded-md"
          :class="
            mode === 'login'
              ? 'bg-white shadow text-slate-900'
              : 'text-slate-500'
          "
          @click="mode = 'login'"
        >
          Login
        </button>
        <button
          class="flex-1 py-2 text-sm font-medium rounded-md"
          :class="
            mode === 'guest'
              ? 'bg-white shadow text-slate-900'
              : 'text-slate-500'
          "
          @click="mode = 'guest'"
        >
          Guest
        </button>
      </div>

      <form
        v-if="mode === 'login'"
        @submit.prevent="submitLogin"
        class="space-y-4"
      >
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1"
            >Email</label
          >
          <input
            v-model="email"
            type="email"
            required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1"
            >Password</label
          >
          <input
            v-model="password"
            type="password"
            required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
          />
        </div>

        <p v-if="error" class="text-sm text-red-600">{{ error }}</p>

        <button
          type="submit"
          :disabled="loading"
          class="w-full rounded-lg bg-black py-2.5 text-white font-medium hover:bg-slate-800 disabled:opacity-50"
        >
          {{ loading ? "Signing in..." : "Sign In" }}
        </button>
      </form>

      <div v-else class="space-y-4">
        <p class="text-sm text-slate-600 text-center">
          Continue as guest to submit a support ticket without logging in.
        </p>

        <button
          @click="continueAsGuest"
          class="w-full rounded-lg bg-black py-2.5 text-white font-medium hover:bg-slate-800"
        >
          Continue as Guest
        </button>
      </div>

      <div class="mt-6 rounded-lg bg-slate-50 p-4 text-sm">
        <p class="font-semibold text-slate-900 mb-2">Demo Accounts:</p>
        <ul class="space-y-1 text-slate-700">
          <li><strong>Admin:</strong> admin@ticketflow.app / Admin@123</li>
          <li>
            <strong>Agent:</strong> agent.support@ticketflow.app / Agent@123
          </li>
          <li>
            <strong>Customer:</strong> customer@example.com / Customer@123
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

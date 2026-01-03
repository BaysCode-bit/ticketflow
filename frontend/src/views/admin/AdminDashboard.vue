<script setup lang="ts">
import { useRouter } from "vue-router";

const router = useRouter();

function goToCreateUser() {
  router.push("/admin/users/create");
}
</script>

<template>
  <div class="min-h-screen bg-slate-100 px-6 py-8">
    <div class="max-w-6xl mx-auto">
      <h1 class="text-2xl font-semibold text-slate-900 mb-1">
        Admin Dashboard
      </h1>
      <p class="text-sm text-slate-500 mb-8">
        Manage users, agents, and ticket distribution.
      </p>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div
          class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col justify-between"
        >
          <div>
            <h2 class="text-lg font-semibold text-slate-900 mb-1">
              User Management
            </h2>
            <p class="text-sm text-slate-500">
              Create admin or agent accounts and manage agent categories.
            </p>
          </div>

          <button
            class="mt-6 rounded-lg bg-black py-2.5 text-sm font-semibold text-white hover:bg-slate-800"
            @click="goToCreateUser"
          >
            Create User
          </button>
        </div>

        <div
          class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col justify-between"
        >
          <div>
            <h2 class="text-lg font-semibold text-slate-900 mb-1">
              Ticket Distribution
            </h2>
            <p class="text-sm text-slate-500">
              Monitor ticket assignment and SLA performance.
            </p>
          </div>

          <button
            disabled
            class="mt-6 rounded-lg border border-slate-300 py-2.5 text-sm text-slate-400 cursor-not-allowed"
          >
            Coming Soon
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

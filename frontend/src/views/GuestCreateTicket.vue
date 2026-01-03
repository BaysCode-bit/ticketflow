<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import api from "../lib/axios";

const router = useRouter();

const form = ref({
  name: "",
  email: "",
  subject: "",
  category: "",
  priority: "medium",
  description: "",
});

const loading = ref(false);
const success = ref(false);
const ticketId = ref<number | null>(null);
const errorMessage = ref("");

const submitTicket = async () => {
  errorMessage.value = "";
  loading.value = true;

  try {
    const res = await api.post("/guest/tickets", {
      name: form.value.name,
      email: form.value.email,
      subject: form.value.subject,
      category: form.value.category,
      priority: form.value.priority,
      message: form.value.description,
    });

    ticketId.value = res.data?.ticket_id ?? null;
    success.value = true;
  } catch (e: any) {
    errorMessage.value =
      e?.response?.data?.message ||
      "Failed to submit ticket. Please try again.";
  } finally {
    loading.value = false;
  }
};

const resetForm = () => {
  form.value = {
    name: "",
    email: "",
    subject: "",
    category: "",
    priority: "medium",
    description: "",
  };
  success.value = false;
  ticketId.value = null;
};
</script>

<template>
  <div class="min-h-screen bg-gray-50 flex flex-col items-center py-10 px-4">
    <div class="flex flex-col items-center mb-10">
      <img
        src="../../../public/logo.svg"
        alt="TicketFlow Logo"
        class="w-14 h-14 mb-4"
      />
      <h1 class="text-3xl font-bold text-slate-900">Submit a Guest Ticket</h1>
      <p class="mt-2 text-slate-500">
        Contact our support team without creating an account
      </p>
    </div>

    <div class="mx-auto max-w-4xl space-y-8">
      <div
        v-if="success"
        class="rounded-xl border border-emerald-200 bg-emerald-50 p-6"
      >
        <h2 class="text-lg font-semibold text-emerald-700">
          Ticket submitted successfully
        </h2>
        <p class="mt-2 text-emerald-700">
          Our support team will contact you via email.
        </p>
        <p v-if="ticketId" class="mt-1 text-sm text-emerald-600">
          Ticket ID: <strong>#{{ ticketId }}</strong>
        </p>

        <button
          class="mt-6 w-full rounded-lg bg-black py-3 text-white hover:bg-slate-800"
          @click="resetForm"
        >
          Submit another ticket
        </button>
      </div>

      <div
        v-else
        class="rounded-xl border border-slate-200 bg-white p-8 shadow-sm"
      >
        <h2 class="text-xl font-semibold text-slate-900">Ticket Details</h2>
        <p class="mt-1 text-slate-500">
          Please fill out the form below to create your support ticket.
        </p>

        <div class="mt-8">
          <h3 class="mb-4 font-semibold text-slate-900">Contact Information</h3>

          <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <input
              v-model="form.name"
              type="text"
              placeholder="Full Name *"
              class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
            />
            <input
              v-model="form.email"
              type="email"
              placeholder="Email Address *"
              class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
            />
          </div>
        </div>

        <div class="mt-8">
          <h3 class="mb-4 font-semibold text-slate-900">Ticket Information</h3>

          <input
            v-model="form.subject"
            type="text"
            placeholder="Subject *"
            class="mb-4 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
          />

          <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-2">
            <select
              v-model="form.category"
              class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
            >
              <option value="">Select Category *</option>
              <option value="shipping">Shipping</option>
              <option value="payment">Payment</option>
              <option value="account">Account</option>
              <option value="product">Product</option>
              <option value="return_refund">Return & Refund</option>
            </select>

            <select
              v-model="form.priority"
              class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
            >
              <option value="low">Low</option>
              <option value="medium">Medium</option>
              <option value="high">High</option>
            </select>
          </div>

          <textarea
            v-model="form.description"
            rows="5"
            placeholder="Describe your issue in detail *"
            class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
          />
        </div>

        <p v-if="errorMessage" class="mt-4 text-sm text-red-600">
          {{ errorMessage }}
        </p>

        <button
          class="mt-6 w-full rounded-lg bg-black py-3 text-white hover:bg-slate-800 disabled:opacity-50"
          :disabled="loading"
          @click="submitTicket"
        >
          {{ loading ? "Submitting..." : "Submit Ticket" }}
        </button>
      </div>

      <div class="rounded-xl border border-slate-200 bg-white p-8 shadow-sm">
        <h3 class="mb-4 font-semibold text-slate-900">Guest Ticket Process</h3>
        <ol class="space-y-2 text-slate-600">
          <li>1. Submit your ticket</li>
          <li>2. Receive confirmation email</li>
          <li>3. Communicate via email</li>
          <li>4. Issue resolution</li>
        </ol>
      </div>

      <div class="rounded-xl border border-slate-200 bg-white p-8 shadow-sm">
        <h3 class="mb-4 font-semibold text-slate-900">Create an Account</h3>
        <ul class="mb-6 space-y-2 text-slate-600">
          <li>✔ Track all your tickets</li>
          <li>✔ Faster responses</li>
          <li>✔ Access to dashboard</li>
          <li>✔ Better support experience</li>
        </ul>

        <button
          class="w-full rounded-lg border border-slate-300 py-3 hover:bg-slate-50"
          @click="router.push('/register')"
        >
          Create Free Account
        </button>
      </div>

      <div class="rounded-xl border border-slate-200 bg-white p-8 shadow-sm">
        <h3 class="mb-4 font-semibold text-slate-900">Support Hours</h3>

        <div class="space-y-2 text-slate-600">
          <div class="flex justify-between">
            <span>Monday – Friday</span>
            <span>9:00 AM – 6:00 PM</span>
          </div>
          <div class="flex justify-between">
            <span>Saturday</span>
            <span>10:00 AM – 4:00 PM</span>
          </div>
          <div class="flex justify-between">
            <span>Sunday</span>
            <span>Closed</span>
          </div>
          <div class="mt-2 flex justify-between font-medium">
            <span>Response Time</span>
            <span>Within 24 hours</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "../stores/auth";

import Login from "../views/Login.vue";
import GuestCreateTicket from "../views/GuestCreateTicket.vue";

import Dashboard from "../views/Dashboard.vue";
import Tickets from "../views/Tickets.vue";
import TicketDetail from "../views/TicketDetail.vue";

import AdminDashboard from "../views/admin/AdminDashboard.vue";
import AdminCreateUser from "../views/admin/AdminCreateUser.vue";
import AdminEditAgentCategories from "../views/admin/AdminEditAgentCategories.vue";

import AgentDashboard from "../views/agent/AgentDashboard.vue";

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: "/login", name: "login", component: Login },

    {
      path: "/guest/tickets",
      name: "guest-create-ticket",
      component: GuestCreateTicket,
    },

    {
      path: "/",
      name: "dashboard",
      component: Dashboard,
      meta: { requiresAuth: true },
    },
    {
      path: "/tickets",
      name: "tickets",
      component: Tickets,
      meta: { requiresAuth: true },
    },
    {
      path: "/tickets/:id",
      name: "ticket-detail",
      component: TicketDetail,
      meta: { requiresAuth: true },
    },

    {
      path: "/admin",
      name: "admin-dashboard",
      component: AdminDashboard,
      meta: { requiresAuth: true, role: "admin" },
    },
    {
      path: "/admin/users/create",
      name: "admin-create-user",
      component: AdminCreateUser,
      meta: { requiresAuth: true, role: "admin" },
    },
    {
      path: "/admin/agents/categories",
      name: "admin-edit-agent-categories",
      component: AdminEditAgentCategories,
      meta: { requiresAuth: true, role: "admin" },
    },

    {
      path: "/agent",
      name: "agent-dashboard",
      component: AgentDashboard,
      meta: { requiresAuth: true, role: "agent" },
    },
  ],
});

router.beforeEach(async (to) => {
  const auth = useAuthStore();

  if (to.meta.requiresAuth && !auth.token) return { name: "login" };

  if (auth.token && !auth.user) {
    try {
      await auth.fetchUser();
    } catch {
      auth.logout();
      return { name: "login" };
    }
  }

  if (to.name === "dashboard" && auth.user?.role === "admin")
    return { name: "admin-dashboard" };

  if (to.name === "dashboard" && auth.user?.role === "agent")
    return { name: "agent-dashboard" };

  if (to.meta.role && auth.user?.role !== to.meta.role)
    return { name: "dashboard" };
});

export default router;

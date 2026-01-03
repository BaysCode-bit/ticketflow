import { defineStore } from "pinia";
import api from "../services/api";

interface User {
  id: number;
  name: string;
  email: string;
  role: "admin" | "agent" | "customer";
}

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: null as User | null,
    token: localStorage.getItem("token") as string | null,
    loading: false,
    error: null as string | null,
  }),

  actions: {
    async login(email: string, password: string) {
      this.loading = true;
      this.error = null;

      try {
        const res = await api.post("/login", { email, password });

        const token: string = res.data.token;
        localStorage.setItem("token", token);
        this.token = token;

        await this.fetchUser();
      } catch (err: any) {
        this.error = err.response?.data?.message || "Login failed";
        throw err;
      } finally {
        this.loading = false;
      }
    },

    async fetchUser() {
      const res = await api.get("/me");
      this.user = res.data;
    },

    logout() {
      this.user = null;
      this.token = null;
      localStorage.removeItem("token");
    },
  },
});

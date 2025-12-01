import { defineStore } from "pinia";
import axiosInstance from '@/plugins/axios' 
import type { MusicDashboardResponse } from "@/models/MusicDashboard";

export const useDashboardStore = defineStore("dashboardStore", {
  state: () => ({
    loading: false,
    data: null as MusicDashboardResponse | null,
  }),

  actions: {
    async fetchDashboard() {
      this.loading = true;
      try {
        const res = await axiosInstance.get<MusicDashboardResponse>("/dashboard/musics");
        this.data = res.data;
      } finally {
        this.loading = false;
      }
    }
  }
});

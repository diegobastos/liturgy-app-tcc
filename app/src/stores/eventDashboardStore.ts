import { defineStore } from "pinia"
import axiosInstance from "@/plugins/axios"
import type { EventDashboardResponse } from "@/models/EventDashboard"

export const useDashboardStore = defineStore("eventDashboardStore", {
  state: () => ({
    loading: false,
    data: null as EventDashboardResponse | null,
  }),

  getters: {
    summary: (state) => state.data?.summary ?? {
      agendados: 0,
      mes_atual: 0,
      semana_atual: 0
    },

    nextEvents: (state) => state.data?.nextEvents ?? [],

    hasEvents: (state) => (state.data?.nextEvents?.length ?? 0) > 0
  },

  actions: {
    async fetchDashboard() {
      this.loading = true
      try {
        const res = await axiosInstance.get<EventDashboardResponse>("/dashboard/events")
        this.data = res.data
      } finally {
        this.loading = false
      }
    }
  }
})

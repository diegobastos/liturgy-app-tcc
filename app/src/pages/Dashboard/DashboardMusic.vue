<template>
<div class="container-fluid py-2">

  <h4 class="mb-3 text-secondary">Estatísticas de Músicas</h4>

  <!-- Linha 1 — Summary compactado -->
  <div class="row g-2 mb-3">

    <div class="col-md-4">
      <Card 
        class="small-card"
        title="Músicas diferentes"
        :value="summary.unique_musics_used"
        icon="bi bi-music-note-list"
      />
    </div>

    <div class="col-md-4">
      <Card 
        class="small-card"
        title="Execuções totais"
        :value="summary.total_music_plays"
        icon="bi bi-graph-up-arrow"
      />
    </div>

    <div class="col-md-4" v-if="summary?.most_used">
      <Card 
        class="small-card"
        title="Mais usada"
        :value="summary.most_used.name"
        :subtitle="summary.most_used.total_usage + ' vezes'"
        icon="bi bi-star-fill"
      />
    </div>

  </div>

  <!-- Linha 2 — Gráfico + tabelas -->
  <div class="row g-3">

    <!-- Gráfico -->
    <div class="col-lg-7">
      <div class="card shadow-sm border-0 p-2 small-card">
        <h6 class="fw-semibold mb-2">Top músicas nos últimos 8 eventos</h6>
        <apexchart
          height="180"
          type="bar"
          :options="chartOptions"
          :series="chartSeries"
        />
      </div>
    </div>

    <!-- Uso geral -->
    <div class="col-lg-3">
      <div class="card shadow-sm border-0 small-card">
        <div class="card-body py-2 px-3">
          <h6 class="fw-semibold mb-2">Uso geral</h6>

          <table class="table table-sm table-hover">
            <tbody>
              <tr v-for="item in data.generalUsage" :key="item.id">
                <td class="fw-semibold small">{{ item.name }}</td>
                <td>
                  <span class="badge bg-primary">{{ item.total_usage }}</span>
                </td>
              </tr>
            </tbody>
          </table>

        </div>
      </div>
    </div>

    <!-- Última vez usada -->
    <div class="col-lg-2">
      <div class="card shadow-sm border-0 small-card">
        <div class="card-body py-2 px-3">
          <h6 class="fw-semibold mb-2">Última vez usada</h6>

          <ul class="list-group list-group-flush small">
            <li 
              v-for="item in data.lastUsed" 
              :key="item.id"
              class="list-group-item d-flex justify-content-between"
            >
              <span>{{ item.name }}</span>
              <span class="text-muted small">{{ item.last_used_at }}</span>
            </li>
          </ul>

        </div>
      </div>
    </div>

  </div>
</div>

</template>

<script setup lang="ts">
import { onMounted, computed } from "vue";
import { useDashboardStore } from "@/stores/musicDashboardStore";
import Card from "@/components/dashboard/Card.vue";

const store = useDashboardStore();

onMounted(() => store.fetchDashboard());

const data = computed(() => store.data ?? {
  generalUsage: [],
  lastUsed: [],
  topLast8Events: [],
  summary: {
    unique_musics_used: 0,
    total_music_plays: 0,
    most_used: {
      name: "",
      total_usage: 0
    }
  }
});

const summary = computed(() => store.data?.summary ?? {
  unique_musics_used: 0,
  total_music_plays: 0,
  most_used: {
    name: "",
    total_usage: 0
  }
});

const chartSeries = computed(() => [
  {
    name: "Uso",
    data: store.data?.topLast8Events.map(i => i.usage_last_events) ?? []
  }
]);

const chartOptions = computed(() => ({
  theme: {
    mode: "light"
  },
  chart: {
    toolbar: { show: false },
  },
  plotOptions: {
    bar: { borderRadius: 6, columnWidth: "45%" }
  },
  colors: ["#0d6efd"],
  dataLabels: {
    enabled: false
  },
  xaxis: {
    categories: store.data?.topLast8Events.map(i => i.name) ?? [],
    labels: { style: { colors: "#666" } }
  },
  yaxis: {
    labels: { style: { colors: "#666" } }
  }
}));
</script>

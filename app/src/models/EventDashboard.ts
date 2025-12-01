export interface DashboardEventSummary {
  agendados: number;      // total de eventos futuros
  mes_atual: number;      // eventos no mês atual
  semana_atual: number;   // eventos nesta semana
}

export interface NextEventItem {
  id: number;
  name: string;
  start_at: string;
  end_at: string;
  scale_type: string | null;
  role: string | null;
  status: "PENDING" | "CONFIRMED" | "REPLACED" | "ABSENT";
}

export interface EventDashboardResponse {
  summary: DashboardEventSummary;
  nextEvents: NextEventItem[];
}

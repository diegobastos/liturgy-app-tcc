export interface MusicUsageItem {
  id: number;
  name: string;
  total_usage?: number;
  last_used_at?: string | null;
  usage_last_events?: number;
}

export interface DashboardSummary {
  unique_musics_used: number;
  total_music_plays: number;
  most_used: {
    name: string;
    total_usage: number;
  };
}

export interface MusicDashboardResponse {
  generalUsage: MusicUsageItem[];
  lastUsed: MusicUsageItem[];
  topLast8Events: MusicUsageItem[];
  summary: DashboardSummary;
}

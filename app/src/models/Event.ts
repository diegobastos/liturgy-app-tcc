import type { BaseModel } from "./BaseModel"
import type { Music } from "./Music"

export interface EventActivity extends BaseModel{
  event_id?: number
  music_id: number | null
  position: number
  notes: string
  created_at?: string
  updated_at?: string
  music?: Music | null
}

export interface Event extends BaseModel{
  name: string
  start_at?: string
  end_at?: string
  activities: EventActivity[]  
}

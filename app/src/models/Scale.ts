import type { BaseModel } from "./BaseModel"

export interface ScaleMember {
    user_id: number
    role?: string
    status?: string
}

export interface Scale extends BaseModel {
  event_id?: number
  scale_type_id?: number
  notes?: string
  start_at?: string
  end_at?: string
  members: Array<ScaleMember>
}

export interface ScaleType extends BaseModel {
  name: string
  slug: string
  description?: string
}

export interface ScaleResponse {
  id: number
  notes?: string
  start_at?: string | null
  end_at?: string | null
  type?: { id: number; name: string; slug: string }
  event?: {
    id: number
    name: string
    start_at?: string
    end_at?: string
  } | null
  members: {
    id: number
    role: string
    status: string
    user: { id: number; name: string }
  }[]
}
import type { BaseModel } from "./BaseModel"

export interface Music extends BaseModel{
  name: string
  author: string
  tone: string
  time_signature?: string
  lyrics?: null
  music_score?: null
}

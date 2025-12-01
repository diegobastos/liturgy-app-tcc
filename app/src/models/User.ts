import type { BaseModel } from "./BaseModel"

export interface UserPublic extends BaseModel {
  name: string
  email: string
  username: string
  active: number
  timezone: string
  roles?: null
}

export interface User extends BaseModel {
  name: string
  email: string
  username: string
  active: number
  timezone: string
  roles?: null 
}

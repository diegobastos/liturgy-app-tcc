import { BaseService } from './BaseService'
import type { UserPublic } from '../models/User'

export class UserService extends BaseService<UserPublic> {
  constructor() {
    super()
    this.endpoint = '/users'
  }
}

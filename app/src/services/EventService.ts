import { BaseService } from './BaseService'
import type { Event } from '../models/Event'

export class EventService extends BaseService<Event> {
  constructor() {
    super()
    this.endpoint = '/events'
  }
}
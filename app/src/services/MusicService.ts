import { BaseService } from './BaseService'
import type { Music } from '../models/Music'

export class MusicService extends BaseService<Music> {
  constructor() {
    super()
    this.endpoint = '/musics'
  }
}

import { BaseService } from './BaseService'
import type { Scale, ScaleMember, ScaleType } from '../models/Scale'

export class ScaleService extends BaseService<Scale> {
  constructor() {
    super()
    this.endpoint = '/scales'
  }

  async createPartial(payload: Partial<Scale>) {
    const response = await this.api.post('/scales', payload)
    return response.data.data
  }

  async updatePartial(id: string, payload: Partial<Scale>) {
    return this.api.put(`/scales/${id}`, payload)
  }  
}

export class ScaleTypeService extends BaseService<ScaleType> {
  constructor() {
    super()
    this.endpoint = '/scale-types'
  }    
}

export class ScaleMemberService extends BaseService<ScaleMember> {
  constructor() {
    super()
    this.endpoint = '/scales'
  }

  async addMembers(scaleId: number, members: ScaleMember[]) {
    const response = await this.api.post(`${this.endpoint}/${scaleId}/members`, { members })
    return response.data
  }

  async updateMembers(scaleId: number, members: ScaleMember[]) {
    const response = await this.api.patch(`${this.endpoint}/${scaleId}/members`, { members })
    return response.data
  }

  async deleteMembers(scaleId: number) {
    const response = await this.api.delete(`${this.endpoint}/${scaleId}/members`)
    return response.data
  }
}



import axiosInstance from '../plugins/axios'
import type { AxiosInstance } from 'axios'

export interface PaginatedResponse<T> {
  data: T[]
  totalPages: number
  pagination: {
    page: number
    limit: number
    last: number
    hasMorePages: boolean
  }
}

export abstract class BaseService<T> {
  protected api: AxiosInstance = axiosInstance
  protected endpoint: string = ''

  private static _instances = new Map<string, any>()

  static getInstance<S extends BaseService<any>>(this: new () => S): S {
    const name = this.name
    if (!BaseService._instances.has(name)) {
      BaseService._instances.set(name, new this())
    }
    return BaseService._instances.get(name)
  }

  async list<R = T>(
    params: Record<string, any> = {},
    page = 1,
    limit = 10
  ): Promise<PaginatedResponse<R>> {
    const response = await this.api.get(this.endpoint, { params: { ...params, page, limit } })
    return response.data
  }

  async all(params: Record<string, any> = {}): Promise<T> {
    const response = await this.api.get(this.endpoint, { params: { ...params} })
    return response.data
  }

  async get<R = T>(id: number): Promise<R> {
    const response = await this.api.get(`${this.endpoint}/${id}`)
    return response.data.data
  }

  async create(data: T): Promise<T> {
    const response = await this.api.post(this.endpoint, data)
    return response.data.data
  }

  async update(id: number, data: T): Promise<T> {
    const response = await this.api.patch(`${this.endpoint}/${id}`, data)
    return response.data.data
  }

  async delete(id: number): Promise<void> {
    await this.api.delete(`${this.endpoint}/${id}`)
  }
}


import axiosInstance from "../plugins/axios";
import type { AxiosInstance, AxiosError } from "axios";

export interface LoginPayload {
  email: string;
  password: string;
}

export interface LoginResponse {
  token: string;
  name: string;
  email: string;
}

export interface User {
  id: number;
  name: string;
  email: string;
}

export class AuthService {
  private static _instance: AuthService;
  private api: AxiosInstance;
  private endpoint = "/auth";

  private constructor() {
    this.api = axiosInstance;
  }

  static getInstance(): AuthService {
    if (!this._instance) {
      this._instance = new AuthService();
    }
    return this._instance;
  }

  async login(payload: LoginPayload): Promise<LoginResponse> {
    try {
      const { data } = await this.api.post(`${this.endpoint}/login`, payload);

      if (!data.token) throw new Error("Token não retornado pela API.");

      // Salva o token no localStorage
      localStorage.setItem("token", data.token);

      // Configura o token no header para as próximas requisições
      this.api.defaults.headers.common[
        "Authorization"
      ] = `Bearer ${data.token}`;

      return {
        token: data.token,
        name: data.name,
        email: data.email,
      };
    } catch (err) {
      const error = err as AxiosError<any>;
      if (error.response?.status === 401) {
        throw new Error(
          error.response.data.message || "Usuário ou senha inválidos."
        );
      }
      throw new Error("Erro ao tentar autenticar. Tente novamente.");
    }
  }

  async getMe(): Promise<User> {
    const { data } = await this.api.get<User>(`${this.endpoint}/me`);
    return data;
  }

  async logout(): Promise<void> {
    try {
      await this.api.post(`${this.endpoint}/logout`);
    } catch (err) {
      console.warn("Erro ao sair (ignorando)", err);
    } finally {
      localStorage.removeItem("token");
      delete this.api.defaults.headers.common["Authorization"];
    }
  }
}

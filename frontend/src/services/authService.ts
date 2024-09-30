import api from './api';
import { User } from '../types/api';

export const login = async (email: string, password: string): Promise<User> => {
  await api.get('/sanctum/csrf-cookie'); // Nécessaire pour Laravel Sanctum
  const response = await api.post<User>('/login', { email, password });
  return response.data;
};

export const logout = async (): Promise<void> => {
  await api.post('/logout');
};

export const getUser = async (): Promise<User> => {
  const response = await api.get<User>('/user');
  return response.data;
};
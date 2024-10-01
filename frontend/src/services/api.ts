import axios, { AxiosInstance, AxiosResponse, AxiosError } from 'axios';

const api: AxiosInstance = axios.create({
  baseURL: process.env.REACT_APP_API_URL || 'http://localhost:80:80/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  withCredentials: true, // Important pour les cookies CSRF avec Laravel Sanctum
});

// Intercepteur pour les réponses
api.interceptors.response.use(
  (response: AxiosResponse) => response,
  async (error: AxiosError) => {
    if (error.response?.status === 401) {
      // Gérer la déconnexion ou le rafraîchissement du token ici
    }
    return Promise.reject(error);
  }
);

export default api;
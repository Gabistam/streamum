import api from './api';
import { Movie, PaginatedResponse } from '../types/api';

export const getMovies = async (page: number = 1): Promise<PaginatedResponse<Movie>> => {
  const response = await api.get<PaginatedResponse<Movie>>('/movies', { params: { page } });
  return response.data;
};

export const getMovie = async (id: number): Promise<Movie> => {
  const response = await api.get<Movie>(`/movies/${id}`);
  return response.data;
};

export const searchMovies = async (query: string, page: number = 1): Promise<PaginatedResponse<Movie>> => {
  const response = await api.get<PaginatedResponse<Movie>>('/movies/search', { params: { query, page } });
  return response.data;
};
export interface User {
    id: number;
    name: string;
    email: string;
  }
  
  export interface Movie {
    id: number;
    title: string;
    description: string;
    release_date: string;
    // Ajoutez d'autres propriétés selon votre modèle
  }
  
  export interface PaginatedResponse<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  }
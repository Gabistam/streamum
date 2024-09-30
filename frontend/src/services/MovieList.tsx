// src/components/MovieList.tsx

import React from 'react';
import { useApi } from '../hooks/useApi';
import { getMovies } from '../services/movieService';
import { Movie, PaginatedResponse } from '../types/api';

const MovieList: React.FC = () => {
  const { data: paginatedMovies, loading, error, execute } = useApi<PaginatedResponse<Movie>, [number?]>(getMovies);

  React.useEffect(() => {
    execute();
  }, [execute]);

  if (loading) return <div>Loading...</div>;
  if (error) return <div>Error: {error}</div>;

  return (
    <div>
      {paginatedMovies?.data.map((movie) => (
        <div key={movie.id}>{movie.title}</div>
      ))}
    </div>
  );
};

export default MovieList;
// src/pages/Movies.tsx

import React from 'react';
import { useParams } from 'react-router-dom';
import MovieDetails from '../components/MovieDetails';
import MovieList from '../components/MovieList';

const Movies: React.FC = () => {
  const { id } = useParams<{ id: string }>();

  if (id) {
    return (
      <div className="container mx-auto px-4 py-8">
        <MovieDetails />
      </div>
    );
  }

  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold mb-6">Liste des Films</h1>
      <MovieList />
    </div>
  );
};

export default Movies;
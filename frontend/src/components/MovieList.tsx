// src/components/MovieList.tsx

import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { getMovies } from '../services/movieService';
import { Movie } from '../types/api';

const MovieList: React.FC = () => {
  const [movies, setMovies] = useState<Movie[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchMovies = async () => {
      try {
        setLoading(true);
        const response = await getMovies();
        setMovies(response.data);
        setLoading(false);
      } catch (err) {
        setError('Une erreur est survenue lors du chargement des films.');
        setLoading(false);
      }
    };

    fetchMovies();
  }, []);

  if (loading) return <div>Chargement...</div>;
  if (error) return <div>{error}</div>;

  return (
    <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      {movies.map((movie) => (
        <div key={movie.id} className="border rounded-lg overflow-hidden shadow-lg">
          <img src={movie.poster_path} alt={movie.title} className="w-full h-64 object-cover" />
          <div className="p-4">
            <h2 className="font-bold text-xl mb-2">{movie.title}</h2>
            <p className="text-gray-700 text-base">{movie.release_date}</p>
            <Link 
              to={`/movie/${movie.id}`}
              className="mt-4 inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600"
            >
              Voir les détails
            </Link>
          </div>
        </div>
      ))}
    </div>
  );
};

export default MovieList;
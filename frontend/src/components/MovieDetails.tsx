// src/components/MovieDetails.tsx

import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { getMovie } from '../services/movieService';
import { Movie } from '../types/api';

const MovieDetails: React.FC = () => {
  const { id } = useParams<{ id: string }>();
  const [movie, setMovie] = useState<Movie | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchMovie = async () => {
      try {
        setLoading(true);
        const response = await getMovie(parseInt(id, 10));
        setMovie(response.data);
        setLoading(false);
      } catch (err) {
        setError('Une erreur est survenue lors du chargement des détails du film.');
        setLoading(false);
      }
    };

    fetchMovie();
  }, [id]);

  if (loading) return <div>Chargement...</div>;
  if (error) return <div>{error}</div>;
  if (!movie) return <div>Film non trouvé.</div>;

  return (
    <div className="max-w-4xl mx-auto p-4">
      <h1 className="text-3xl font-bold mb-4">{movie.title}</h1>
      <div className="flex flex-col md:flex-row">
        <img src={movie.poster_path} alt={movie.title} className="w-full md:w-1/3 rounded-lg shadow-lg" />
        <div className="md:ml-6 mt-4 md:mt-0">
          <p className="text-gray-600 mb-2">Date de sortie : {movie.release_date}</p>
          <p className="text-gray-800 mb-4">{movie.overview}</p>
          <p className="text-gray-600 mb-2">Note moyenne : {movie.vote_average}/10</p>
          <Link 
            to="/movies" 
            className="inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600"
          >
            Retour à la liste
          </Link>
        </div>
      </div>
    </div>
  );
};

export default MovieDetails;
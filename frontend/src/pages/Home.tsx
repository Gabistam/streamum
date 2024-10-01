import React from 'react';
import { useEffect, useState } from 'react';
import { useAuth } from '../hooks/useAuth';
import { Link } from 'react-router-dom';
import { getMovies } from '../services/movieService';
import { Movie } from '../types/api'; // Assurez-vous que ce chemin est correct

const Home: React.FC = () => {
  const [movies, setMovies] = useState<Movie[]>([]);
  const { user } = useAuth();

  useEffect(() => {
    const fetchMovies = async () => {
      try {
        const response = await getMovies();
        setMovies(response.data);
      } catch (error) {
        console.error('Erreur lors de la récupération des films:', error);
      }
    };

    fetchMovies();
  }, []);

  return (
    <div>
      <h1>Bienvenue sur notre site de streaming</h1>
      {user ? (
        <p>Bonjour, {user.name}!</p>
      ) : (
        <p>Veuillez vous <Link to="/login">connecter</Link> pour accéder à tout le contenu.</p>
      )}
      <h2>Films populaires</h2>
      <ul>
        {movies.map((movie) => (
          <li key={movie.id}>{movie.title}</li>
        ))}
      </ul>
    </div>
  );
};

export default Home;
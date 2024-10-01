import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../hooks/useAuth';
import { getPopularMovies } from '../services/movieService';
import { Movie } from '../types/api';
import SearchBar from '../components/SearchBar';

const Home: React.FC = () => {
  const [popularMovies, setPopularMovies] = useState<Movie[]>([]);
  const { user } = useAuth();

  useEffect(() => {
    const fetchPopularMovies = async () => {
      try {
        const movies = await getPopularMovies();
        setPopularMovies(movies.slice(0, 4)); 
      } catch (error) {
        console.error('Erreur lors de la récupération des films populaires:', error);
      }
    };
  
    fetchPopularMovies();
  }, []);

  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-4xl font-bold mb-8">Bienvenue sur StreamFlix</h1>
      
      <div className="mb-8">
        <h2 className="text-2xl font-semibold mb-4">Découvrez nos films populaires</h2>
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
          {popularMovies.map((movie) => (
            <div key={movie.id} className="border rounded-lg overflow-hidden shadow-lg">
              <img src={movie.poster_path} alt={movie.title} className="w-full h-64 object-cover" />
              <div className="p-4">
                <h3 className="font-bold text-xl mb-2 truncate">{movie.title}</h3>
              </div>
            </div>
          ))}
        </div>
      </div>

      {!user && (
        <div className="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-8" role="alert">
          <p className="font-bold">Accédez à plus de contenu</p>
          <p>
            <Link to="/login" className="text-blue-500 hover:underline">Connectez-vous</Link> ou 
            <Link to="/register" className="text-blue-500 hover:underline ml-1">inscrivez-vous</Link> 
            pour profiter de notre catalogue complet et de fonctionnalités exclusives !
          </p>
        </div>
      )}

      {user && (
        <>
          <div className="mb-8">
            <h2 className="text-2xl font-semibold mb-4">Rechercher un film</h2>
            <SearchBar />
          </div>
          
          <div className="mb-8">
            <h2 className="text-2xl font-semibold mb-4">Recommandations personnalisées</h2>
            <p>Basées sur vos préférences... (à implémenter)</p>
          </div>
          
          <div>
            <h2 className="text-2xl font-semibold mb-4">Continuer à regarder</h2>
            <p>Vos films et séries en cours... (à implémenter)</p>
          </div>
        </>
      )}
    </div>
  );
};

export default Home;
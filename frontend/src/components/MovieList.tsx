import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { getMovies } from '../services/movieService';
import { Movie } from '../types/api';
import InfiniteScroll from './InfiniteScroll';

interface MovieListProps {
  initialMovies?: Movie[];
}

const MovieList: React.FC<MovieListProps> = ({ initialMovies = [] }) => {
  const [movies, setMovies] = useState<Movie[]>(initialMovies);
  const [page, setPage] = useState(1);
  const [loading, setLoading] = useState(false);
  const [hasMore, setHasMore] = useState(true);

  const loadMovies = async () => {
    if (loading) return;
    setLoading(true);
    try {
      const response = await getMovies(page);
      const newMovies = response.data;
      setMovies(prevMovies => [...prevMovies, ...newMovies]);
      setPage(prevPage => prevPage + 1);
      setHasMore(newMovies.length > 0);
    } catch (error) {
      console.error('Error loading movies:', error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (initialMovies.length === 0) {
      loadMovies();
    }
  }, []);

  return (
    <InfiniteScroll
      loadMore={loadMovies}
      hasMore={hasMore}
      isLoading={loading}
      loader={<div className="text-center py-4">Chargement des films...</div>}
      endMessage={<div className="text-center py-4">Vous avez vu tous les films !</div>}
    >
      <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        {movies.map((movie) => (
          <div key={movie.id} className="border rounded-lg overflow-hidden shadow-lg">
            <img src={movie.poster_path} alt={movie.title} className="w-full h-64 object-cover" />
            <div className="p-4">
              <h2 className="font-bold text-xl mb-2">{movie.title}</h2>
              <p className="text-gray-700 text-base">{movie.release_date}</p>
              <Link 
                to={`/movies/${movie.id}`}
                className="mt-4 inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600"
              >
                Voir les détails
              </Link>
            </div>
          </div>
        ))}
      </div>
    </InfiniteScroll>
  );
};

export default MovieList;
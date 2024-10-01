import React, { useState } from 'react';
import axios from 'axios';
import SearchBar from '../components/SearchBar';
import MovieList from '../components/MovieList';
import { Movie } from '../types/api';

const SearchPage: React.FC = () => {
  const [searchTerm, setSearchTerm] = useState<string>('');
  const [results, setResults] = useState<Movie[]>([]);
  const [loading, setLoading] = useState<boolean>(false);
  const [error, setError] = useState<string | null>(null);

  const handleSearch = async (term: string) => {
    setSearchTerm(term);
    setLoading(true);
    setError(null);

    if (term) {
      try {
        const response = await axios.get<Movie[]>(`/api/movies/search`, {
          params: { query: term },
        });
        setResults(response.data);
      } catch (err) {
        setError('Une erreur s\'est produite lors de la recherche.');
      } finally {
        setLoading(false);
      }
    } else {
      setResults([]);
      setLoading(false);
    }
  };

  return (
    <div className="container mx-auto p-4">
      <h1 className="text-2xl font-bold mb-4">Recherche de Films</h1>
      <SearchBar onSearch={handleSearch} />
      {loading && <p>Chargement...</p>}
      {error && <p className="text-red-500">{error}</p>}
      {results.length > 0 && <MovieList initialMovies={results} />}
      {results.length === 0 && !loading && <p>Aucun résultat trouvé.</p>}
    </div>
  );
};

export default SearchPage;
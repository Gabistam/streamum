import React from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../hooks/useAuth';

const Header: React.FC = () => {
  const { user, logout } = useAuth();

  const handleLogout = async () => {
    try {
      await logout();
      // Redirection après déconnexion si nécessaire
    } catch (error) {
      console.error('Logout failed', error);
    }
  };

  return (
    <header className="bg-gray-800 text-white">
      <div className="container mx-auto px-4 py-6 flex justify-between items-center">
        <Link to="/" className="text-2xl font-bold">StreamApp</Link>
        <nav>
          <ul className="flex space-x-4">
            <li><Link to="/" className="hover:text-gray-300">Home</Link></li>
            <li><Link to="/movies" className="hover:text-gray-300">Movies</Link></li>
            {user ? (
              <>
                <li><Link to="/profile" className="hover:text-gray-300">Profile</Link></li>
                <li><button onClick={handleLogout} className="hover:text-gray-300">Logout</button></li>
              </>
            ) : (
              <li><Link to="/login" className="hover:text-gray-300">Login</Link></li>
            )}
          </ul>
        </nav>
      </div>
    </header>
  );
};

export default Header;
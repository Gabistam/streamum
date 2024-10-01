// src/components/LoginForm.tsx

import React, { useState } from 'react';
import { login } from '../services/authService';
import { useAuth } from '../hooks/useAuth'; // Assurez-vous que ce hook existe
import { useNavigate } from 'react-router-dom';

const LoginForm: React.FC = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
//   const { setUser } = useAuth(); // Supposons que votre hook useAuth fournit cette fonction
  const navigate = useNavigate();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    try {
      const response = await login(email, password);
    //   setUser(response.data.user); // Mettez à jour l'état de l'utilisateur
      navigate('/'); // Redirigez vers la page d'accueil après la connexion
    } catch (error) {
      setError('Échec de la connexion. Veuillez vérifier vos identifiants.');
      console.error('Login failed', error);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-4">
      <div>
        <label htmlFor="email" className="block mb-1">Email</label>
        <input
          type="email"
          id="email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          required
          className="w-full px-3 py-2 border rounded"
        />
      </div>
      <div>
        <label htmlFor="password" className="block mb-1">Mot de passe</label>
        <input
          type="password"
          id="password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          required
          className="w-full px-3 py-2 border rounded"
        />
      </div>
      {error && <p className="text-red-500">{error}</p>}
      <button type="submit" className="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">
        Se connecter
      </button>
    </form>
  );
};

export default LoginForm;
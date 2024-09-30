import React from 'react';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';
import Layout from './components/Layout';
import Home from './pages/Home';
import Movies from './pages/Movies';
import Login from './pages/Login';
import Profile from './pages/Profile';
import { AuthProvider } from './context/AuthContext';

const App: React.FC = () => {
  return (
    <AuthProvider>
      <Router>
        <Layout>
          <Switch>
            <Route exact path="/" component={Home} />
            <Route path="/movies" component={Movies} />
            <Route path="/login" component={Login} />
            <Route path="/profile" component={Profile} />
          </Switch>
        </Layout>
      </Router>
    </AuthProvider>
  );
};

export default App;
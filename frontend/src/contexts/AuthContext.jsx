import { createContext, useState, useEffect } from 'react';
import { getToken, saveToken, removeToken, setAuthToken } from '../services/authService';
import * as authService from '../services/authService';

export const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    checkAuth();
  }, []);

  const checkAuth = async () => {
    const token = getToken();
    if (token) {
      setAuthToken(token);
      try {
        const userData = await authService.getCurrentUser();
        setUser(userData);
      } catch (error) {
        removeToken();
      }
    }
    setLoading(false);
  };

  const login = async (email, password) => {
    const { token, user: userData } = await authService.login(email, password);
    saveToken(token);
    setUser(userData);
  };

  const logout = async () => {
    try {
      await authService.logout();
    } catch (error) {
      console.error('Logout error:', error);
    } finally {
      removeToken();
      setUser(null);
    }
  };

  const value = {
    user,
    loading,
    login,
    logout,
    isAuthenticated: !!user,
  };

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};

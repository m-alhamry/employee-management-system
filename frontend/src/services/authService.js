import api, { setAuthToken } from './api';

export { setAuthToken };

export const login = async (email, password) => {
  const response = await api.post('/login', { email, password });
  return response.data;
};

export const logout = async () => {
  const response = await api.post('/logout');
  return response.data;
};

export const getCurrentUser = async () => {
  const response = await api.get('/user');
  return response.data;
};

export const saveToken = (token) => {
  localStorage.setItem('token', token);
  setAuthToken(token);
};

export const getToken = () => {
  return localStorage.getItem('token');
};

export const removeToken = () => {
  localStorage.removeItem('token');
  setAuthToken(null);
};

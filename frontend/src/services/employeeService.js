import api from './api';

export const getAllEmployees = async () => {
  const response = await api.get('/employees');
  return response.data.data;
};

export const getEmployee = async (id) => {
  const response = await api.get(`/employees/${id}`);
  return response.data.data;
};

export const createEmployee = async (employeeData) => {
  const response = await api.post('/employees', employeeData);
  return response.data.data;
};

export const updateEmployee = async (id, employeeData) => {
  const response = await api.put(`/employees/${id}`, employeeData);
  return response.data.data;
};

export const deleteEmployee = async (id) => {
  await api.delete(`/employees/${id}`);
};

import React, { createContext, useContext, useEffect, useState } from 'react';
import axiosInstance from '../api/axiosInstance';

interface AuthContextType {
  isAuthenticated: boolean;
  login: (email: string, password: string) => Promise<boolean>;
  logout: () => void;
  register: (email: string, password: string, birth_date: string, username: string) => Promise<boolean>;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [isAuthenticated, setIsAuthenticated] = useState(false);

  useEffect(() => {
    const token = localStorage.getItem('token');
    if (token) {
      setIsAuthenticated(true);
    }
  }, []);

  const login = async (email: string, password: string): Promise<boolean> => {
    try {
      const response = await axiosInstance.post('/login', {
        email,
        password,
      });
      const { token } = response.data;
      localStorage.setItem('token', token);
      setIsAuthenticated(true);
      return true;
    } catch (error) {
      console.error('Login failed:', error);
      return false;
    }
  };

  const register = async (email: string, password: string, birth_date: string, username: string): Promise<boolean> => {
    try {
      const response = await axiosInstance.post('/register', {
        email,
        password,
        birth_date,
        username,
      });
      const token = response.data.token;
      if (token) {
        localStorage.setItem('token', token);
        setIsAuthenticated(true);
      }
      return true;
    } catch (error) {
      console.error('Registration failed:', error);
      return false;
    }
  };

  const logout = () => {
    localStorage.removeItem('token');
    setIsAuthenticated(false);
  };

  return <AuthContext.Provider value={{ isAuthenticated, login, logout, register }}>{children}</AuthContext.Provider>;
};


export const useAuth = (): AuthContextType => {
  const context = useContext(AuthContext);
  if (!context) throw new Error('useAuth must be used within an AuthProvider');
  return context;
};

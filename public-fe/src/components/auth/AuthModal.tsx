import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:5000';

const AuthModal: React.FC = () => {
  const [activeTab, setActiveTab] = useState<'login' | 'register'>('login');
  const [errorMsg, setErrorMsg] = useState('');
  const { login, register } = useAuth();
  const [isLoading, setIsLoading] = useState(false);

  const [form, setForm] = useState({
    email: '',
    password: '',
    confirmPassword: '',
    dob: {
      day: '',
      month: '',
      year: '',
    },
  });

  const handleInput = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    if (['day', 'month', 'year'].includes(name)) {
      setForm((prev) => ({
        ...prev,
        dob: {
          ...prev.dob,
          [name]: value,
        },
      }));
    } else {
      setForm((prev) => ({ ...prev, [name]: value }));
    }
    setErrorMsg('');
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setErrorMsg('');
    setIsLoading(true);

    if (activeTab === 'login') {
      const success = await login(form.email, form.password);
      if (!success) {
        setErrorMsg('Email or password is incorrect.');
      }
    } else {
      if (form.password !== form.confirmPassword) {
        setErrorMsg('Passwords do not match.');
        setIsLoading(false);
        return;
      }

      const birth_date = `${form.dob.year}-${form.dob.month.padStart(2, '0')}-${form.dob.day.padStart(2, '0')}`;
      const username = form.email.split('@')[0];

      const success = await register(form.email, form.password, birth_date, username);
      if (!success) {
        setErrorMsg('Registration failed. Please try again.');
      } else {
        setActiveTab('login');
      }
    }

    setIsLoading(false);
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-opacity-50">
      <div className="bg-white rounded-2xl shadow-xl w-[450px] p-10 font-plus-jakarta-sans">
        <h2 className="text-2xl font-bold text-black mb-6 text-start">Welcome to TicketBuzz</h2>
        <div className="flex justify-center">
          <div className="relative w-[80%] flex justify-between mb-6 border-b border-gray-200">
            <button
              className={`w-1/2 pb-2 text-center font-medium text-lg ${activeTab === 'login' ? 'text-black' : 'text-gray-400'}`}
              onClick={() => {
                setActiveTab('login');
                setErrorMsg('');
              }}
            >
              Login
            </button>
            <button
              className={`w-1/2 pb-2 text-center font-medium text-lg ${activeTab === 'register' ? 'text-black' : 'text-gray-400'}`}
              onClick={() => {
                setActiveTab('register');
                setErrorMsg('');
              }}
            >
              Register
            </button>
            <span className={`absolute bottom-0 h-[1px] w-1/2 bg-[#A94AFD] transition-all duration-300 ${activeTab === 'login' ? 'left-0' : 'left-1/2'}`}></span>
          </div>
        </div>

        <form onSubmit={handleSubmit} className="space-y-4">
          <input type="email" name="email" required placeholder="you@example.com" value={form.email} onChange={handleInput} className="w-full border rounded-md px-4 py-2 placeholder-gray-400" />

          <input type="password" name="password" required placeholder="Password" value={form.password} onChange={handleInput} className="w-full border rounded-md px-4 py-2 placeholder-gray-400" />

          {activeTab === 'register' && (
            <>
              <input type="password" name="confirmPassword" required placeholder="Confirm Password" value={form.confirmPassword} onChange={handleInput} className="w-full border rounded-md px-4 py-2 placeholder-gray-400" />

              <div className="flex justify-between gap-3">
                <select name="month" required value={form.dob.month} onChange={handleInput} className="w-1/3 border rounded-md px-3 py-2 text-gray-600">
                  <option value="">MM</option>
                  {Array.from({ length: 12 }, (_, i) => (
                    <option key={i + 1} value={String(i + 1).padStart(2, '0')}>
                      {String(i + 1).padStart(2, '0')}
                    </option>
                  ))}
                </select>

                <select name="day" required value={form.dob.day} onChange={handleInput} className="w-1/3 border rounded-md px-3 py-2 text-gray-600">
                  <option value="">DD</option>
                  {Array.from({ length: 31 }, (_, i) => (
                    <option key={i + 1} value={String(i + 1).padStart(2, '0')}>
                      {String(i + 1).padStart(2, '0')}
                    </option>
                  ))}
                </select>

                <select name="year" required value={form.dob.year} onChange={handleInput} className="w-1/3 border rounded-md px-3 py-2 text-gray-600">
                  <option value="">YYYY</option>
                  {Array.from({ length: 100 }, (_, i) => {
                    const year = new Date().getFullYear() - i;
                    return (
                      <option key={year} value={year}>
                        {year}
                      </option>
                    );
                  })}
                </select>
              </div>
            </>
          )}

          {errorMsg && <p className="text-red-500 text-sm text-center">{errorMsg}</p>}

          <button type="submit" disabled={isLoading} className={`w-full py-2 mt-2 rounded-md font-semibold text-white transition ${isLoading ? 'bg-gray-400 cursor-not-allowed' : 'bg-gradient-to-r from-[#A94AFD] to-[#F871BF]'}`}>
            {isLoading ? (
              <span className="flex items-center justify-center gap-2">
                <svg className="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                  <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                  <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                Loading...
              </span>
            ) : activeTab === 'login' ? (
              'Sign in'
            ) : (
              'Sign up'
            )}
          </button>

          <button type="button" className="w-full py-2 border rounded-md font-medium text-black mt-2" onClick={() => (window.location.href = `${API_BASE_URL}/auth/redirect`)}>
            Continue with Google
          </button>
        </form>
      </div>
    </div>
  );
};

export default AuthModal;

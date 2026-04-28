import { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

const OAuthCallback = () => {
  const navigate = useNavigate();

  useEffect(() => {
    const params = new URLSearchParams(window.location.search);
    const token = params.get('token');

    if (token) {
      localStorage.setItem('token', token);
      navigate('/');
    } else {
      navigate('/');
    }
  }, []);

  return <div className="h-screen flex justify-center items-center text-xl text-gray-700">Logging in with Google...</div>;
};

export default OAuthCallback;

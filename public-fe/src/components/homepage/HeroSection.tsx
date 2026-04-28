import React from 'react';
import banner from '../../assets/bannerhomepage.svg';
import { useNavigate } from 'react-router-dom';

const HeroSection: React.FC = () => {
  const navigate = useNavigate();
  return (
    <section className="w-full h-[500px] bg-cover bg-center text-white flex items-center" style={{ backgroundImage: `url(${banner})` }}>
      <div className="w-full h-full flex items-center">
        <div className="max-w-5xl px-36">
          <h1 className="text-5xl md:text-7xl font-extrabold mb-6 leading-16 font-plus-jakarta-sans">
            Find Your Next <br />
            Concert Experience
          </h1>
          <p className="text-xl md:text-2xl mb-8 max-w-2xl font-plus-jakarta-sans font-medium">Discover and book tickets to the hottest concerts and live music events happening near you.</p>
          <div className="flex gap-4 font-plus-jakarta-sans font-bold">
            <button onClick={() => navigate('/catalog')} className="bg-[#7F27D4] hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg relative z-10">
              Explore Events
            </button>
            <button className="bg-white text-[#5925BB] hover:bg-gray-100 font-semibold py-3 px-6 rounded-lg">How it Works</button>
          </div>
        </div>
      </div>
    </section>
  );
};

export default HeroSection;

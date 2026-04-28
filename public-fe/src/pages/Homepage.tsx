import HeroSection from '../components/homepage/HeroSection';
import CategorySection from '../components/homepage/CategorySection';
import FeaturedEvents from '../components/homepage/FeaturedEvents';
import UpcomingTable from '../components/homepage/UpcomingTable';
import AuthModal from '../components/auth/AuthModal';
import { useAuth } from '../context/AuthContext';
import { useState } from 'react';
import { FeaturedType } from '../api/concertApi';

const Homepage = () => {
  const { isAuthenticated } = useAuth();
  const [selectedGenres, setSelectedGenres] = useState<number[]>([]);
  const [featuredTypes, setFeaturedTypes] = useState<FeaturedType[]>([]);

  const handleGenreChange = (genreIds: number[]) => {
    setSelectedGenres(genreIds);
  };

  const handleFeaturedChange = (featured: FeaturedType[]) => {
    setFeaturedTypes(featured);
  };

  return (
    <main>
      {!isAuthenticated && <AuthModal />}
      <HeroSection />
      <CategorySection onChange={handleGenreChange} onFeaturedChange={handleFeaturedChange} />
      <FeaturedEvents selectedGenres={selectedGenres} featuredTypes={featuredTypes} />
      <UpcomingTable />
    </main>
  );
};

export default Homepage;

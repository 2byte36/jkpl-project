import HeroSection from '../components/homepage/HeroSection';
import BrowseEvent from '../components/catalog/BrowseEvent';
import CategorySection from '../components/homepage/CategorySection';
import { FeaturedType } from '../api/concertApi';
import { useState } from 'react';
import { useSearchParams } from 'react-router-dom';
import AuthModal from '../components/auth/AuthModal';
import { useAuth } from '../context/AuthContext';

const Catalog = () => {
  const { isAuthenticated } = useAuth();
  const [searchParams, setSearchParams] = useSearchParams();
  const [selectedGenres, setSelectedGenres] = useState<number[]>([]);
  const [featuredTypes, setFeaturedTypes] = useState<FeaturedType[]>([]);

  const updateURLParams = (genres: number[], featured: FeaturedType[]) => {
    const params: Record<string, string | string[]> = {
      page: '1',
    };

    if (searchParams.get('search')) {
      params.search = searchParams.get('search')!;
    }

    if (genres.length > 0) {
      params['genre_ids[]'] = genres.map(String);
    }

    featured.forEach((type) => {
      params[type] = '1';
    });

    setSearchParams(params as any);
  };

  const handleGenreChange = (genreIds: number[]) => {
    setSelectedGenres(genreIds);
    updateURLParams(genreIds, featuredTypes);
  };

  const handleFeaturedChange = (featured: FeaturedType[]) => {
    setFeaturedTypes(featured);
    updateURLParams(selectedGenres, featured);
  };

  return (
    <main>
      {!isAuthenticated && <AuthModal />}
      <HeroSection />
      <CategorySection onChange={handleGenreChange} onFeaturedChange={handleFeaturedChange} />
      <BrowseEvent />
    </main>
  );
};

export default Catalog;

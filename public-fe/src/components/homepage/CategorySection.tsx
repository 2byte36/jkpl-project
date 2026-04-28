import React, { useEffect, useState } from 'react';
import { Genre, getAllGenres } from '../../api/genreApi';
import { FeaturedType } from '../../api/concertApi';

interface CategorySectionProps {
  onChange: (selectedIds: number[]) => void;
  onFeaturedChange: (featuredTypes: FeaturedType[]) => void;
}

const CategorySection: React.FC<CategorySectionProps> = ({ onChange, onFeaturedChange }) => {
  const [genres, setGenres] = useState<Genre[]>([]);
  const [selectedGenres, setSelectedGenres] = useState<number[]>([]);
  const [selectedFeatured, setSelectedFeatured] = useState<FeaturedType[]>([]);

  const featuredNames = ['today', 'this week', 'trending'];

  useEffect(() => {
    const fetchGenres = async () => {
      const genreData = await getAllGenres();
      setGenres(genreData);
    };

    fetchGenres();
  }, []);

  const featuredCategories = genres.filter((genre) => featuredNames.includes(genre.name.toLowerCase()));

  const regularGenres = genres.filter((genre) => !featuredNames.includes(genre.name.toLowerCase()));

  const toggleGenre = (id: number) => {
    const updated = selectedGenres.includes(id) ? selectedGenres.filter((gid) => gid !== id) : [...selectedGenres, id];

    setSelectedGenres(updated);
    onChange(updated);
  };

  const toggleFeatured = (genre: Genre) => {
    const featuredType = genre.name.toLowerCase().replace(' ', '_') as FeaturedType;
    const updated = selectedFeatured.includes(featuredType) ? selectedFeatured.filter((type) => type !== featuredType) : [...selectedFeatured, featuredType];

    setSelectedFeatured(updated);
    onFeaturedChange(updated);
  };

  const isGenreSelected = (id: number) => selectedGenres.includes(id);
  const isFeaturedSelected = (genre: Genre) => {
    const featuredType = genre.name.toLowerCase().replace(' ', '_') as FeaturedType;
    return selectedFeatured.includes(featuredType);
  };

  return (
    <div className="w-full py-8 bg-white drop-shadow-lg flex justify-center overflow-hidden">
      <div className="flex gap-8 font-medium font-plus-jakarta-sans text-2xl overflow-x-auto px-20 no-scrollbar">
        {featuredCategories.map((genre) => (
          <button
            key={`featured-${genre.id}`}
            onClick={() => toggleFeatured(genre)}
            className={`px-10 py-3 rounded-lg text-sm font-medium whitespace-nowrap transition
              ${isFeaturedSelected(genre) ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white' : 'bg-gray-100 text-black hover:bg-gray-200'}`}
          >
            {genre.name}
          </button>
        ))}
        {regularGenres.map((genre) => (
          <button
            key={genre.id}
            onClick={() => toggleGenre(genre.id)}
            className={`px-10 py-3 rounded-lg text-sm font-medium whitespace-nowrap transition
              ${isGenreSelected(genre.id) ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white' : 'bg-gray-100 text-black hover:bg-gray-200'}`}
          >
            {genre.name}
          </button>
        ))}
      </div>
    </div>
  );
};

export default CategorySection;

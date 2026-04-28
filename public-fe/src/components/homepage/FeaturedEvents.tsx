import React, { useEffect, useState } from 'react';
import EventCard from './EventCard';
import arrow_left from '../../assets/arrow-left.svg';
import arrow_right from '../../assets/arrow-right.svg';
import { getAllConcert, FeaturedType } from '../../api/concertApi';
import { EventItem } from '../../types/event';
import { useSearchParams } from 'react-router-dom';
import { formatDateRange } from '../../utils/dateFormatter';

interface FeaturedEventsProps {
  selectedGenres: number[];
  featuredTypes: FeaturedType[];
}

const FeaturedEvents: React.FC<FeaturedEventsProps> = ({ selectedGenres, featuredTypes }) => {
  const [events, setEvents] = useState<EventItem[]>([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [isLoading, setIsLoading] = useState(false);
  const [, setSearchParams] = useSearchParams();
  const ITEMS_PER_PAGE = 3;

  const fetchConcerts = async (page: number, genres: number[], featured: FeaturedType[]) => {
    setIsLoading(true);
    try {
      const { concerts, lastPage } = await getAllConcert(page, ITEMS_PER_PAGE, genres, featured);
      const mapped = concerts.map(mapConcertToEventItem);
      setEvents(mapped);
      setTotalPages(lastPage);
    } catch (err) {
      console.error('Failed to fetch concerts:', err);
    } finally {
      setIsLoading(false);
    }
  };

  useEffect(() => {
    setCurrentPage(1);
  }, [selectedGenres, featuredTypes]);

  useEffect(() => {
    const params: Record<string, string | string[]> = {
      page: currentPage.toString(),
    };

    if (selectedGenres.length > 0) {
      params['genre_ids[]'] = selectedGenres.map((id) => id.toString());
    }

    featuredTypes.forEach((type) => {
      params[type] = '1';
    });

    setSearchParams(params as any);
    fetchConcerts(currentPage, selectedGenres, featuredTypes);
  }, [currentPage, selectedGenres, featuredTypes]);

  return (
    <section className="py-12 bg-[#F3F4F6] text-center">
      <h2 className="text-5xl font-bold mb-8 text-start mx-32 font-plus-jakarta-sans">Featured Events</h2>

      {isLoading ? (
        <p className="text-center text-xl mb-10 text-gray-500">Loading events...</p>
      ) : events.length === 0 ? (
        <div className="text-center py-12">
          <p className="text-xl text-gray-500 mb-4">No events found</p>
        </div>
      ) : (
        <div className="flex flex-wrap justify-center gap-6 mb-8">
          {events.map((event) => (
            <EventCard key={event.id} event={event} />
          ))}
        </div>
      )}

      {events.length > 0 && (
        <div className="flex justify-center items-center gap-4">
          <button onClick={() => setCurrentPage((prev) => Math.max(prev - 1, 1))} disabled={currentPage === 1} className="text-gray-500 disabled:opacity-30 hover:opacity-75 transition-opacity">
            <img src={arrow_left} alt="Previous page" />
          </button>

          {[...Array(totalPages)].map((_, idx) => (
            <button
              key={idx}
              onClick={() => setCurrentPage(idx + 1)}
              className={`rounded-lg px-3 py-2 font-medium transition-all duration-200 ${currentPage === idx + 1 ? 'bg-gradient-to-b from-[#902EDF] to-[#FB67B6] text-white' : 'text-black hover:bg-gray-200'}`}
            >
              {idx + 1}
            </button>
          ))}

          <button onClick={() => setCurrentPage((prev) => Math.min(prev + 1, totalPages))} disabled={currentPage === totalPages} className="text-gray-500 disabled:opacity-30 hover:opacity-75 transition-opacity">
            <img src={arrow_right} alt="Next page" />
          </button>
        </div>
      )}
    </section>
  );
};

const mapConcertToEventItem = (concert: any): EventItem => {
  const date = formatDateRange(concert.concert_start, concert.concert_end);
  const time = new Date(concert.concert_start).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

  const priceRange = concert.min_price && concert.max_price ? `IDR ${concert.min_price.toLocaleString()} - IDR ${concert.max_price.toLocaleString()}` : concert.min_price ? `IDR ${concert.min_price.toLocaleString()}` : 'Free';

  return {
    id: concert.id,
    title: concert.name,
    category: concert.genres?.[0]?.name || 'Music',
    location: `${concert.venue.name}, ${concert.venue.city.name}`,
    date,
    time,
    priceRange,
    image: concert.link_poster,
  };
};

export default FeaturedEvents;

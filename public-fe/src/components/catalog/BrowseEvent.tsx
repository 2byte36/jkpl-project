import React, { useEffect, useState } from 'react';
import EventCard from '../homepage/EventCard';
import arrow_left from '../../assets/arrow-left.svg';
import arrow_right from '../../assets/arrow-right.svg';
import { FeaturedType, getAllConcert } from '../../api/concertApi';
import { EventItem } from '../../types/event';
import { useSearchParams } from 'react-router-dom';
import { formatDateRange } from '../../utils/dateFormatter';

const ITEMS_PER_PAGE = 6;

const BrowseEvent: React.FC = () => {
  const [events, setEvents] = useState<EventItem[]>([]);
  const [totalPages, setTotalPages] = useState(1);
  const [isLoading, setIsLoading] = useState(false);

  const [searchParams, setSearchParams] = useSearchParams();

  const currentPage = parseInt(searchParams.get('page') || '1');
  const searchQuery = searchParams.get('search') || '';
  const selectedGenres = searchParams.getAll('genre_ids[]').map(Number);
  const featuredTypes: FeaturedType[] = ['today', 'this_week', 'trending'].filter((t) => searchParams.get(t) === '1') as FeaturedType[];

  const buildParams = (page: number) => {
    const params: Record<string, string | string[]> = {
      page: page.toString(),
    };

    if (searchQuery) params.search = searchQuery;
    if (selectedGenres.length > 0) params['genre_ids[]'] = selectedGenres.map(String);
    featuredTypes.forEach((type) => (params[type] = '1'));

    return params;
  };

  const fetchConcerts = async () => {
    setIsLoading(true);
    try {
      const { concerts, lastPage } = await getAllConcert(currentPage, ITEMS_PER_PAGE, selectedGenres, featuredTypes, searchQuery);
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
    fetchConcerts();
  }, [searchParams]);

  return (
    <section className="py-12 bg-[#F3F4F6] text-center">
      <h2 className="text-5xl font-bold mb-8 text-start mx-32 font-plus-jakarta-sans">BrowseEvent</h2>

      {isLoading ? (
        <p className="text-center text-xl mb-10 text-gray-500">Loading events...</p>
      ) : events.length === 0 ? (
        <div className="text-center py-16">
          <p className="text-2xl text-gray-500 mb-2">No events found {searchQuery ? `for "${searchQuery}"` : ''}</p>
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
          <button
            onClick={() => {
              const page = Math.max(currentPage - 1, 1);
              setSearchParams(buildParams(page));
            }}
            disabled={currentPage === 1}
            className="text-gray-500 disabled:opacity-30"
          >
            <img src={arrow_left} alt="arrow-left" />
          </button>

          {[...Array(totalPages)].map((_, idx) => {
            const pageNumber = idx + 1;
            return (
              <button
                key={idx}
                onClick={() => {
                  setSearchParams(buildParams(pageNumber));
                }}
                className={`rounded-lg px-3 py-2 font-medium ${currentPage === pageNumber ? 'bg-gradient-to-b from-[#902EDF] to-[#FB67B6] text-white' : 'text-black'}`}
              >
                {pageNumber}
              </button>
            );
          })}

          <button
            onClick={() => {
              const page = Math.min(currentPage + 1, totalPages);
              setSearchParams(buildParams(page));
            }}
            disabled={currentPage === totalPages}
            className="text-gray-500 disabled:opacity-30"
          >
            <img src={arrow_right} alt="arrow-right" />
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

export default BrowseEvent;

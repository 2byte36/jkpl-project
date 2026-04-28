import React, { useEffect, useState } from 'react';
import { getUpcomingConcerts, Concert } from '../../api/concertApi';
import UpcomingRow from './UpcomingRow';
import { UpcomingEvent } from '../../types/upcomingEvent.types';

const UpcomingTable: React.FC = () => {
  const [events, setEvents] = useState<UpcomingEvent[]>([]);

  useEffect(() => {
    const fetchUpcoming = async () => {
      const data: Concert[] = await getUpcomingConcerts();
      const mapped: UpcomingEvent[] = data.map((concert) => {
        const firstTicket = concert.tickets[0];
        const time = new Date(concert.concert_start).toLocaleTimeString([], {
          hour: '2-digit',
          minute: '2-digit',
        });

        return {
          id: concert.id,
          title: concert.name,
          category: concert.genres?.[0]?.name || 'Music',
          date: new Date(concert.concert_start).toLocaleDateString(undefined, {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
          }),
          time,
          venue: concert.venue.name,
          city: concert.venue.city.name,
          priceRange: firstTicket ? `IDR ${firstTicket.price.toLocaleString()}` : 'Free',
          image: concert.link_poster,
        };
      });

      setEvents(mapped);
    };

    fetchUpcoming();
  }, []);

  return (
    <section className="bg-[#F9FAFB] py-12 px-4 md:px-16 lg:px-32 font-plus-jakarta-sans">
      <h2 className="text-5xl font-bold mb-10 text-left">Upcoming in Your Area</h2>
      <div className="w-full overflow-x-auto">
        <table className="w-full bg-white overflow-hidden min-w-[800px]">
          <thead>
            <tr className="bg-[#F3F4F6] text-left text-lg text-black">
              <th className="py-3 font-normal px-4 w-[30%]">Event</th>
              <th className="py-3 font-normal w-[15%]">Date & Time</th>
              <th className="py-3 font-normal w-[20%]">Venue</th>
              <th className="py-3 font-normal w-[25%]">Price</th>
              <th className="py-3 font-normal w-[15%]"></th>
            </tr>
          </thead>
          <tbody>
            {events.map((event, index) => (
              <UpcomingRow key={event.id} event={event} isFirst={index === 0} />
            ))}
          </tbody>
        </table>
      </div>
    </section>
  );
};

export default UpcomingTable;

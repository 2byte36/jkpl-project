import React from 'react';
import { EventItem } from '../../types/event';
import calendar from '../../assets/calendar.svg';
import { useNavigate } from 'react-router-dom';

interface Props {
  event: EventItem;
}

const EventCard: React.FC<Props> = ({ event }) => {
  const navigate = useNavigate();
  return (
    <div className="w-[450px] h-[480px] bg-white rounded-2xl shadow-sm overflow-hidden flex flex-col">
      <div className="w-full h-[250px]">
        <img src={event.image} alt={event.title} className="w-full h-full object-cover" />
      </div>

      <div className="flex flex-col gap-1 flex-grow p-4 text-left font-plus-jakarta-sans">
        <div className="flex justify-between items-start">
          <h3 className="text-2xl font-bold max-w-[80%] line-clamp-2">{event.title}</h3>
          <span className="bg-[#71717124] px-5 py-1 text-lg rounded-lg whitespace-nowrap">{event.category}</span>
        </div>

        <p className="text-lg text-gray-400">{event.location}</p>

        <p className="text-md text-black flex items-center gap-1">
          <img src={calendar} alt="calendar-icon"></img>
          {event.date} • {event.time}
        </p>

        <div className="flex justify-between items-center">
          <p className="font-semibold text-lg text-black">{event.priceRange}</p>
          <button onClick={() => navigate(`/concert/${event.id}`)} className="px-5 py-2 bg-gradient-to-r from-[#8F2EE0] to-[#F966B6] text-white font-medium rounded-lg">
            Get Tickets
          </button>
        </div>
      </div>
    </div>
  );
};

export default EventCard;

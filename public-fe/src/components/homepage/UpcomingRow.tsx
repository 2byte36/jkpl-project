import React from 'react';
import { UpcomingEvent } from '../../types/upcomingEvent.types';

interface Props {
  event: UpcomingEvent;
  isFirst?: boolean;
}

const UpcomingRow: React.FC<Props> = ({ event, isFirst }) => {
  return (
    <tr className={`text-left text-sm text-gray-700 font-plus-jakarta-sans bg-[#F9FAFB] ${!isFirst ? 'border-t border-gray-200/80' : ''}`}>
      <td className="py-4 px-4 flex items-center gap-3">
        <img src={event.image} alt={event.title} className="w-12 h-12 rounded-md object-cover" />
        <div>
          <p className="font-medium text-lg text-black truncate">{event.title}</p>
          <p className="text-gray-500">{event.category}</p>
        </div>
      </td>
      <td className="py-4 ">
        <p className="text-black font-medium text-lg">{event.date}</p>
        <p className="text-gray-500">{event.time}</p>
      </td>
      <td className="py-4 ">
        <p className="text-black font-medium text-lg truncate">{event.venue}</p>
        <p className="text-gray-500">{event.city}</p>
      </td>
      <td className="py-4 font-medium text-lg">IDR XXX</td>
      <td className="py-4 ">
        <button className="bg-gradient-to-r from-[#8F2EE0] to-[#F966B6] text-white px-4 py-2 rounded-lg">Coming Soon</button>
      </td>
    </tr>
  );
};

export default UpcomingRow;

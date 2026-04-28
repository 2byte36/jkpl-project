import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { EventItem } from '../types/event';
import TicketCard from '../components/eventDetail/TicketCard';
import OrderSummary from '../components/eventDetail/OrderSummary';
import { getTicketsByConcertId } from '../api/ticketApi';
import { getConcertDetail } from '../api/concertApi';
import { formatDateRange } from '../utils/dateFormatter';
import { createTicketOrder } from '../api/orderApi';
import { useAuth } from '../context/AuthContext';
import AuthModal from '../components/auth/AuthModal';

const EventDetail: React.FC = () => {
  const { isAuthenticated } = useAuth();
  const { id } = useParams<{ id: string }>();
  const [event, setEvent] = useState<EventItem | undefined>();
  const [tab, setTab] = useState<'map' | 'info'>('map');

  const [tickets, setTickets] = useState<
    {
      id: number;
      name: string;
      price: number;
      description?: string;
      quantity: number;
      added: boolean;
      stock: number;
    }[]
  >([]);

  useEffect(() => {
    const fetchData = async () => {
      if (!id) return;

      const concert = await getConcertDetail(id);
      const ticketList = await getTicketsByConcertId(id);

      setEvent({
        id: concert.id,
        title: concert.name,
        description: concert.description,
        date: formatDateRange(concert.concert_start, concert.concert_end),
        time: new Date(concert.concert_start).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
        location: concert.venue.name + ', ' + concert.venue.city_id,
        image: concert.link_poster,
        venueMap: concert.link_venue,
        venueInfo: concert.description,
      });

      setTickets(
        ticketList.map((ticket) => ({
          id: ticket.id,
          name: ticket.name,
          price: ticket.price,
          description: concert.description || 'No description',
          quantity: 0,
          added: false,
          stock: ticket.quota
        }))
      );
    };

    fetchData();
  }, [id]);

  const handleAdd = (ticketId: number) => {
    setTickets((prev) => prev.map((t) => (t.id === ticketId ? { ...t, quantity: 1, added: true } : t)));
  };

  const increment = (ticketId: number) => {
    setTickets((prev) =>
      prev.map((t) =>
        t.id === ticketId && t.quantity < t.stock
          ? { ...t, quantity: t.quantity + 1 }
          : t
      )
    );
  };

  const decrement = (ticketId: number) => {
    setTickets((prev) =>
      prev.map((t) =>
        t.id === ticketId
          ? {
              ...t,
              quantity: Math.max(0, t.quantity - 1),
              added: t.quantity > 1,
            }
          : t
      )
    );
  };

  const orderItems = tickets.filter((t) => t.quantity > 0);

  if (!event) {
    return <div className="text-center py-20">Loading event...</div>;
  } 

  const handleConfirmPayment = async () => {
    if (orderItems.length === 0) return;

    try {
      const payload = {
        tickets: orderItems.map((item) => ({
          ticket_id: item.id,
          quantity: item.quantity,
        })),
      };

      const snapUrl = await createTicketOrder(payload);

      window.location.href = snapUrl;
    } catch (err) {
      console.error('Gagal membuat order:', err);
      alert('Gagal melakukan pemesanan. Silakan coba lagi.');
    }
  };

  return (
    <>
      {!isAuthenticated && <AuthModal />}
      <div className="bg-[#F3F4F6]">
        <div className="relative w-full h-[400px]">
          <img src={event.image} alt={event.title} className="w-full h-full object-cover" />
          <div className="absolute inset-0 bg-black/50 ">
            <div className="flex flex-col justify-center max-w-7xl mx-auto absolute inset-0 px-10">
              <h1 className="text-4xl lg:text-5xl font-bold text-white mb-2">{event.title}</h1>
              <div className="flex items-center gap-4 text-white text-lg">
                <span>{event.date}</span> • <span>{event.location}</span>
              </div>
            </div>
          </div>
        </div>
        <div className="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
          <div className="lg:col-span-2 space-y-8">
            <div className="text-center rounded-xl shadow bg-white">
              <div className="flex justify-center gap-48 pt-5">
                <button className={`relative pb-2 px-5 text-2xl font-semibold transition-colors duration-200 ${tab === 'map' ? 'text-black' : 'text-gray-400'}`} onClick={() => setTab('map')}>
                  Venue Map
                  <div className={`absolute left-0 right-0 bottom-0 h-[2px] mx-auto ${tab === 'map' ? 'bg-[#A94AFD] w-full' : 'bg-gray-300'}`} />
                </button>
                <button className={`relative pb-2 px-5 text-2xl font-semibold transition-colors duration-200 ${tab === 'info' ? 'text-black' : 'text-gray-400'}`} onClick={() => setTab('info')}>
                  Venue Information
                  <div className={`absolute left-0 right-0 bottom-0 h-[2px] mx-auto ${tab === 'info' ? 'bg-[#A94AFD] w-full' : 'bg-gray-300'}`} />
                </button>
              </div>

              <div className="text-left px-4 sm:px-8 md:p-10">
                {tab === 'map' ? <img src={event.venueMap} alt="Venue Map" className="w-full h-[500px] object-cover rounded-md" /> : <p className="text-gray-700 text-base leading-relaxed whitespace-pre-line">{event.venueInfo}</p>}
              </div>
            </div>

            <div className="space-y-6 mt-10 rounded-xl shadow p-10 bg-white">
              <h2 className="text-2xl font-bold mb-4">Ticket Category</h2>
              {tickets.map((ticket) => (
                <TicketCard
                  key={ticket.id}
                  id={ticket.id}
                  name={ticket.name}
                  price={ticket.price}
                  quantity={ticket.quantity}
                  description={ticket.description}
                  added={ticket.added}
                  stock={ticket.stock}
                  onAdd={handleAdd}
                  onIncrement={increment}
                  onDecrement={decrement}
                />
              ))}
            </div>
          </div>

          <OrderSummary onConfirm={handleConfirmPayment} orderItems={orderItems} />
        </div>
      </div>
    </>
  );
};

export default EventDetail;

import { useNavigate } from 'react-router-dom';
import bgMyTicket from '../assets/background-myt-ticket.svg';
import TicketList from '../components/myTicket/TicketList';
import { useAuth } from '../context/AuthContext';
import { useEffect } from 'react';

const MyTicket = () => {
  const { isAuthenticated } = useAuth();

  const navigate = useNavigate();

  useEffect(() => {
    if (!isAuthenticated) {
      navigate('/');
    }
  }, [isAuthenticated, navigate]);

  return (
    <>
      <div className="bg-[#F3F4F6] min-h-screen flex flex-col">
        <div className="relative w-full h-[500px]">
          <img src={bgMyTicket} alt="bg-my-ticket" className="w-full h-full object-cover" />
          <div className="absolute inset-0 bg-black/50 ">
            <div className="flex flex-col justify-center max-w-7xl mx-auto absolute inset-0 px-10">
              <h1 className="text-4xl lg:text-7xl font-bold text-white mb-2">My Tickets</h1>
            </div>
          </div>
        </div>

        <div className="max-w-6xl mx-auto mt-20 mb-10 px-6">
          <div className="bg-white rounded-2xl">
            <TicketList />
          </div>
        </div>
      </div>
    </>
  );
};

export default MyTicket;

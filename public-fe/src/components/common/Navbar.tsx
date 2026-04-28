import { FC, useState, useRef, useEffect } from 'react';
import photoprofile from '../../assets/photoprofile.svg';
import lens from '../../assets/lens.svg';
import { useAuth } from '../../context/AuthContext';
import { useNavigate } from 'react-router-dom';

const Navbar: FC = () => {
  const { isAuthenticated, logout } = useAuth();
  const navigate = useNavigate();
  const [openDropdown, setOpenDropdown] = useState(false);
  const dropdownRef = useRef<HTMLDivElement>(null);
  const [isLoggingOut, setIsLoggingOut] = useState(false);
  const [searchText, setSearchText] = useState('');

  useEffect(() => {
    const handleClickOutside = (e: MouseEvent) => {
      if (dropdownRef.current && !dropdownRef.current.contains(e.target as Node)) {
        setOpenDropdown(false);
      }
    };
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  useEffect(() => {
    if (!isAuthenticated) {
      setIsLoggingOut(false);
    }
  }, [isAuthenticated]);

  const handleLogout = async () => {
    setIsLoggingOut(true);
    setTimeout(() => {
      logout();
      navigate('/');
    }, 1000);
  };

  const handleSearch = (e: React.KeyboardEvent<HTMLInputElement>) => {
    if (e.key === 'Enter') {
      const trimmed = searchText.trim();
      if (trimmed === '') {
        navigate('/catalog');
      } else {
        navigate(`/catalog?search=${encodeURIComponent(trimmed)}&page=1`);
      }
    }
  };

  return (
    <section className="w-full py-3 bg-white fixed shadow-lg top-0 z-50">
      <div className="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
        <button onClick={() => navigate('/')} className="text-4xl font-pacifico bg-gradient-to-r from-[#8F2EE0] to-[#FA66B6] text-transparent bg-clip-text">
          TicketBuzz
        </button>
        <div className="flex-1 mx-6">
          <div className="relative">
            <img src={lens} alt="lens" className="absolute left-7 top-1/2 transform -translate-y-1/2 text-gray-400" width={20} height={20} />
            <input
              type="text"
              placeholder="Search for concerts, artists, or venues"
              value={searchText}
              onChange={(e) => setSearchText(e.target.value)}
              onKeyDown={handleSearch}
              className="w-[60%] pl-16 pr-4 py-2 rounded-full bg-[#F3F4F6] focus:outline-none focus:ring-2 focus:ring-[#8F2EE0] font-plus-jakarta-sans"
            />
          </div>
        </div>

        <div className="relative" ref={dropdownRef}>
          <button onClick={() => setOpenDropdown((prev) => !prev)}>
            <img src={photoprofile} alt="photo-profile" width={50} height={50} />
          </button>

          {openDropdown && isAuthenticated && (
            <div className="absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow-md z-50">
              <button
                onClick={() => {
                  setOpenDropdown(false);
                  navigate('/my-ticket');
                }}
                className="w-full px-4 py-2 text-left text-sm font-medium text-gray-700 hover:bg-gray-100"
              >
                My Ticket
              </button>
              <button
                onClick={handleLogout}
                disabled={isLoggingOut}
                className={`w-full px-4 py-2 text-left text-sm font-medium flex items-center gap-2 ${isLoggingOut ? 'text-gray-500 cursor-not-allowed' : 'text-red-600 hover:bg-gray-100'}`}
              >
                {isLoggingOut ? (
                  <>
                    <svg className="animate-spin h-4 w-4 text-gray-500" viewBox="0 0 24 24">
                      <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                      <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    Logging out...
                  </>
                ) : (
                  'Logout'
                )}
              </button>
            </div>
          )}
        </div>
      </div>
    </section>
  );
};

export default Navbar;

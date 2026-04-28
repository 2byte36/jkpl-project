import React from 'react';

const Footer: React.FC = () => {
  return (
    <footer className="bg-[#1F2937] text-white py-16 px-6 md:px-36 font-plus-jakarta-sans">
      <div className="flex flex-col justify-between h-full min-h-[250px]">
        <div>
          <h1 className="text-4xl font-pacifico bg-gradient-to-r from-[#8F2EE0] to-[#FA66B6] bg-clip-text text-transparent inline-block">TicketBuzz</h1>
          <p className="text-sm text-gray-300 max-w-md mt-2">Your premier destination for discovering and booking the hottest concert tickets.</p>
        </div>
        <div>
          <hr className="border-gray-500 my-6" />
          <p className="text-sm text-gray-400">© {new Date().getFullYear()} TicketBuzz. All rights reserved.</p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;

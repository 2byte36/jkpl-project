import React from 'react';

interface TicketCardProps {
  id: number;
  name: string;
  price: number;
  description?: string;
  quantity: number;
  added: boolean;
  stock: number;
  onAdd: (id: number) => void;
  onIncrement: (id: number) => void;
  onDecrement: (id: number) => void;
}

const TicketCard: React.FC<TicketCardProps> = ({ id, name, price, description = 'Ticket description goes here...', quantity, added, stock, onAdd, onIncrement, onDecrement }) => {
  const remainingStock = stock - quantity;

  return (
    <div className="border border-gray-300 rounded-2xl p-4 space-y-3">
      <div className="flex justify-between items-center">
        <div>
          <p className="font-semibold text-base">{name}</p>
          <p className="text-sm font-semibold text-black">IDR {price.toLocaleString()}</p>

          <p className="text-xs text-gray-500 mt-0.5">Sisa Tiket: {remainingStock}</p>
        </div>

        {!added ? (
          <button onClick={() => onAdd(id)} className="text-sm text-blue-700 border border-gray-300 rounded-md px-4 py-1.5 hover:bg-gray-100">
            Tambah
          </button>
        ) : (
          <div className="flex items-center gap-3 bg-gray-100 w-[120px] px-2 justify-evenly rounded-md ">
            <button onClick={() => onDecrement(id)} className="text-3xl font-bold text-gray-700 hover:text-black">
              -
            </button>
            <span className="font-medium text-xl">{quantity}</span>
            <button onClick={() => onIncrement(id)} disabled={quantity >= stock} className={`text-3xl font-bold ${quantity >= stock ? 'text-gray-400 cursor-not-allowed' : 'text-gray-700 hover:text-black'}`}>
              +
            </button>
          </div>
        )}
      </div>

      <div className="bg-gray-50 text-sm border border-gray-300 text-gray-600 px-4 py-2 rounded-md">{description}</div>
    </div>
  );
};

export default TicketCard;

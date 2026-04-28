import React from 'react';

interface OrderItem {
  id: number;
  name: string;
  price: number;
  quantity: number;
}

interface OrderSummaryProps {
  orderItems: OrderItem[];
  serviceFee?: number;
  onConfirm?: () => void;
  isLoading?: boolean;
}

const OrderSummary: React.FC<OrderSummaryProps> = ({ orderItems, onConfirm }) => {
  const totalTicketPrice = orderItems.reduce((sum, item) => sum + item.quantity * item.price, 0);
  const serviceFee = Math.round(totalTicketPrice * 0.025);
  const totalAmount = totalTicketPrice + serviceFee;

  return (
    <div className="bg-white rounded-lg shadow-md p-6 h-fit sticky top-28">
      <h3 className="text-lg font-semibold mb-4">Order Details</h3>

      {orderItems.length > 0 ? (
        <>
          <ul className="space-y-2 mb-4">
            {orderItems.map((item) => (
              <li key={item.id} className="flex justify-between text-sm text-gray-700">
                <span>{item.name}</span>
                <span>
                  {item.quantity} X IDR {(item.price * item.quantity).toLocaleString()}
                </span>
              </li>
            ))}
            <p className="flex justify-between">
              Service Fee <span>IDR {serviceFee.toLocaleString()}</span>
            </p>
          </ul>

          <div className="border-t pt-2 text-sm text-gray-600">
            <p className="font-bold text-black mt-2">Total: IDR {totalAmount.toLocaleString()}</p>
          </div>

          <button onClick={onConfirm} className="mt-4 w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white py-2 rounded hover:opacity-90">
            Confirm Payment
          </button>
        </>
      ) : (
        <p className="text-sm text-gray-400">Belum ada tiket dipilih.</p>
      )}
    </div>
  );
};

export default OrderSummary;

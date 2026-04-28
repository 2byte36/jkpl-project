import axiosInstance from './axiosInstance';

export interface Ticket {
  id: number;
  concert_id: number;
  name: string;
  price: number;
  description?: string;
  quota: number;
  sales_start: string;
  sales_end: string;
}

export const getTicketsByConcertId = async (concertId: string): Promise<Ticket[]> => {
  try {
    const res = await axiosInstance.get(`/tickets?concert_id=${concertId}`);
    return res.data.data;
  } catch (error) {
    console.error('Failed to fetch tickets:', error);
    return [];
  }
};

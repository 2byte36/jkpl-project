import axiosInstance from './axiosInstance';

interface Venue {
  id: number;
  name: string;
  city?: {
    name: string;
  };
}

export const getAllVenues = async (): Promise<Record<number, string>> => {
  try {
    const res = await axiosInstance.get<{ data: Venue[] }>('/venues');
    const venueMap: Record<number, string> = {};
    res.data.data.forEach((venue) => {
      venueMap[venue.id] = venue.city ? `${venue.name}, ${venue.city.name}` : venue.name;
    });
    return venueMap;
  } catch (err) {
    console.error('Failed to fetch venues:', err);
    return {};
  }
};

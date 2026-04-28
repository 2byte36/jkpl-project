import axiosInstance from './axiosInstance';
import { Concert } from './concertApi';

export interface Genre {
  id: number;
  name: string;
  created_at: string | null;
  updated_at: string | null;
  concerts?: Concert[];
}

type GenreResponse = {
  status: string;
  data: Genre[];
};

export const getAllGenres = async (): Promise<Genre[]> => {
  try {
    const res = await axiosInstance.get<GenreResponse>('/genres');
    return res.data.data;
  } catch (error) {
    console.error('Failed to fetch genres:', error);
    return [];
  }
};

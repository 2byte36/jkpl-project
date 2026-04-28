import axiosInstance from './axiosInstance';

interface Genre {
  id: number;
  name: string;
}

interface Ticket {
  id: number;
  concert_id: number;
  name: string;
  price: number;
  quota: number;
  sales_start: string;
  sales_end: string;
}

interface Venue {
  id: number;
  name: string;
  city: {
    id: number;
    name: string;
  };
}

export interface Concert {
  id: number;
  name: string;
  description: string;
  concert_start: string;
  concert_end: string;
  link_poster: string;
  venue: Venue;
  genres: Genre[];
  tickets: Ticket[];
}

type ApiResponse = {
  status: string;
  data: {
    data: Concert[];
    current_page: number;
    last_page: number;
    total: number;
  };
};

export type FeaturedType = 'today' | 'this_week' | 'trending';

export const getAllConcert = async (page: number, limit: number, genreIds: number[] = [], featuredTypes: FeaturedType[] = [], search: string = ''): Promise<{ concerts: Concert[]; lastPage: number }> => {
  try {
    const params = new URLSearchParams();
    params.append('page', page.toString());
    params.append('limit', limit.toString());

    if (search) {
      params.append('search', search);
    }

    genreIds.forEach((id) => {
      params.append('genre_ids[]', id.toString());
    });

    featuredTypes.forEach((type) => {
      params.append(type, '1');
    });

    const url = `/concerts?${params.toString()}`;
    const response = await axiosInstance.get<ApiResponse>(url);

    return {
      concerts: response.data.data.data,
      lastPage: response.data.data.last_page,
    };
  } catch (error) {
    console.error('Failed to fetch paginated concerts:', error);
    return { concerts: [], lastPage: 1 };
  }
};

export const getConcertDetail = async (id: string) => {
  try {
    const res = await axiosInstance.get(`/concerts/${id}`);
    console.log(res.data.data);
    return res.data.data;
  } catch (error) {
    console.error('Failed to fetch concert detail:', error);
    return null;
  }
};

export const getUpcomingConcerts = async (): Promise<Concert[]> => {
  try {
    const response = await axiosInstance.get<ApiResponse>('/concerts?upcoming=1');
    return response.data.data.data;
  } catch (error) {
    console.error('Failed to fetch upcoming concerts:', error);
    return [];
  }
};

export const searchConcertsByName = async (searchTerm: string, page: number, limit: number): Promise<{ concerts: Concert[]; lastPage: number }> => {
  try {
    const params = new URLSearchParams();
    params.append('search', searchTerm);
    params.append('page', page.toString());
    params.append('limit', limit.toString());

    const response = await axiosInstance.get<ApiResponse>(`/concerts?${params.toString()}`);
    return {
      concerts: response.data.data.data,
      lastPage: response.data.data.last_page,
    };
  } catch (error) {
    console.error('Failed to search concerts:', error);
    return { concerts: [], lastPage: 1 };
  }
};

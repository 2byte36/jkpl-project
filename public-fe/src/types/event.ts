export interface EventItem {
  id: number;
  title: string;
  category?: string;
  location: string;
  date: string;
  time: string;
  priceRange?: string;
  image: string;
  description?: string;
  venueMap?: string;
  venueInfo?: string;
}

export interface Concert {
  id: number;
  name: string;
  concert_start: string;
  concert_end: string;
  link_poster: string;
  genres: { name: string }[];
  venue: {
    name: string;
    city: { name: string };
  };
  tickets: {
    price: number;
  }[];
}

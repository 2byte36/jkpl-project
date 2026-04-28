import axios from "axios";

const API_BASE_URL = process.env.REACT_APP_API_BASE_URL || "http://localhost:5000";
const API_URL = `${API_BASE_URL}/tickets`;

export const getTickets = () => axios.get(API_URL);
export const createTicket = (data) => axios.post(API_URL, data);
export const updateTicket = (id, data) => axios.put(`${API_URL}/${id}`, data);
export const deleteTicket = (id) => axios.delete(`${API_URL}/${id}`);

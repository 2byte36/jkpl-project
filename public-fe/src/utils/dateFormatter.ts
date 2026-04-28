export const formatDateRange = (startStr: string, endStr: string): string => {
  const start = new Date(startStr);
  const end = new Date(endStr);

  const sameDay = start.toDateString() === end.toDateString();

  const month = start.toLocaleString('en-US', { month: 'long' });
  const dayStart = start.getDate();
  const dayEnd = end.getDate();
  const year = start.getFullYear();

  return sameDay ? `${month} ${dayStart}, ${year}` : `${month} ${dayStart}–${dayEnd}, ${year}`;
};

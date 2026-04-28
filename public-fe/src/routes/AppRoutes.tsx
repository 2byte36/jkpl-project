import { Routes, Route } from 'react-router-dom';
import MainLayout from '../layouts/MainLayout';
import Homepage from '../pages/Homepage';
import EventDetail from '../pages/EventDetail';
import Catalog from '../pages/Catalog';
import MyTicket from '../pages/MyTicket';
import OAuthCallback from '../components/auth/OAuthCallback';

const AppRoutes = () => {
  return (
    <>
      <Routes>
        <Route
          path="/"
          element={
            <MainLayout>
              <Homepage />
            </MainLayout>
          }
        />
        <Route
          path="/concert/:id"
          element={
            <MainLayout>
              <EventDetail />
            </MainLayout>
          }
        />
        <Route
          path="/catalog"
          element={
            <MainLayout>
              <Catalog />
            </MainLayout>
          }
        />
        <Route
          path="/my-ticket"
          element={
            <MainLayout>
              <MyTicket />
            </MainLayout>
          }
        />

        <Route path="/oauth/callback" element={<OAuthCallback />} />
      </Routes>
    </>
  );
};

export default AppRoutes;

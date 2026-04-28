import Navbar from '../components/common/Navbar';
import Footer from '../components/common/Footer';

const MainLayout: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  return (
    <div className="flex flex-col min-h-screen">
      <Navbar />
      <main className="w-full mx-auto mt-20 flex-grow">{children}</main>
      <Footer />
    </div>
  );
};

export default MainLayout;

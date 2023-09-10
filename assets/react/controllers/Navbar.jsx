import React, { useState } from 'react';


const Navbar = ({ activeMenuItem, handleMenuItemClick }) => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  const toggleMenu = () => {
    setIsMenuOpen(!isMenuOpen);
  };
  return (
    <nav className="bg-gray-200 p-3 shadow-md">
      <div className="container mx-auto flex items-center justify-between">
        <div className="flex items-center space-x-4">
          <img src="/build/images/logo.svg" alt="Logo" className="h-12 w-auto md:h-20 md:ml-32 md:mr-4 hover:opacity-80 transition-opacity" />
          <ul className="flex space-x-4 mt-6 md:mt-0 md:border-t md:border-gray-300 md:pt-4">
            <li>
              <a
                href="/user"
                className="text-gray-700 hover:text-gray-900 hover:bg-gray-200 font-medium text-base md:text-lg py-2 md:py-4 px-2 md:px-4 rounded-lg"
              >
                Gestion Des Utilisateurs
              </a>
            </li>
            <li>
            <a
  href="/test-rapide" // Replace this with the actual URL you want to redirect to
  className="text-green-600 hover:text-green-800 bg-gray-200 border border-green-600 font-medium text-base md:text-lg py-2 md:py-4 px-2 md:px-4 rounded-lg transition-colors duration-300"
>
  Test Rapide
</a>
              
            </li>
            <li>
            <a
                href="/i/p/address" // Replace this with the actual URL you want to redirect to
                className="text-gray-700 hover:text-gray-900 hover:bg-gray-300 font-medium text-base md:text-lg py-2 md:py-4 px-2 md:px-4 rounded-lg"
              >
                Gestion Des Adresses IP
              </a>


            </li>
          </ul>
        </div>
        <div className="relative">
  <button
    onClick={toggleMenu}
    className="flex items-center text-gray-700 hover:text-gray-900 hover:bg-gray-200 font-medium text-base md:text-lg py-2 md:py-4 px-2 md:px-4 rounded-lg"
  >
    {/* Replace with current user firstname, lastname */}
    <img
      src="/build/images/avatar.png"
      alt="Profile"
      className="h-10 w-10 rounded-full object-cover mx-3"
    />
    Salim Aloui
  </button>
  {isMenuOpen && (
    <div className="absolute right-0 mt-2 w-40 bg-white border border-gray-300 rounded-lg shadow-lg">
      <ul className="py-2">
        <li>
          <a
            href="/i/p/address/profile" // Replace with the actual URL for profile
            className="block px-4 py-2 text-gray-700 hover:bg-gray-200"
          >
            Profile
          </a>
        </li>
        <li>
          <a
            href="/logout" // Replace with the actual URL for logout
            className="block px-4 py-2 text-gray-700 hover:bg-gray-200"
          >
            Déconnexion
          </a>
        </li>
      </ul>
    </div>
  )}
</div>
      </div>
    </nav>
  );
};

export default Navbar;

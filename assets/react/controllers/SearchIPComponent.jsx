import React from 'react';
import { AiOutlineSearch } from 'react-icons/ai';

const SearchIPComponent = () => {
  const handleSubmit = (event) => {
    event.preventDefault();
    // Your search logic goes here, e.g., redirect to the search route with the search query
    const searchQuery = event.target.searchip.value;
    window.location.href = `/searchip?searchip=${encodeURIComponent(searchQuery)}`;
  };

  return (
    <form onSubmit={handleSubmit} className="flex">
      <input
        type="text"
        name="searchip"
        id="searchip"
        placeholder="Chercher selon Addresse"
        className="border border-gray-400 rounded-l px-3 py-2 text-base"
      />
      <button
        type="submit"
        className="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-r flex items-center space-x-1"
      >
        <AiOutlineSearch />
        <span>Chercher</span>
      </button>
    </form>
  );
};

export default SearchIPComponent;

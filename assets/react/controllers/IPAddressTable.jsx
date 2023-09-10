import React, { useState } from 'react';
import { Menu } from '@headlessui/react';
import { BiDotsHorizontalRounded } from 'react-icons/bi';
import SearchIPComponent from './SearchIPComponent';
import axios from 'axios';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEye, faEdit } from '@fortawesome/free-solid-svg-icons';
import { faFlask } from '@fortawesome/free-solid-svg-icons';

const AIPAddressTable = ({ ipaddresses }) => {
  const [currentPage, setCurrentPage] = useState(1);
  const recordsPerPage = 10;
  const lastIndex = currentPage * recordsPerPage;
  const firstIndex = lastIndex - recordsPerPage;
  const records = ipaddresses.slice(firstIndex, lastIndex);
  const totalPages = Math.ceil(ipaddresses.length / recordsPerPage);
  const pageNumbers = [...Array(totalPages).keys()].map((num) => num + 1);

  const handlePageChange = (pageNumber) => {
    setCurrentPage(pageNumber);
  };
  const handlePingTest = async (ipAddress) => {
    
    try {
      const response = await axios.get(`/i/p/address/${ipAddress}/ping-test`);
      console.log(response.data); // Handle the ping test response as needed
    } catch (error) {
      console.error('Error performing ping test:', error);
    }
  };

  return (
    <div className="pl-20 pr-20 py-10 bg-gray-100 min-h-screen">
      <div className="flex justify-between items-center mb-4">
        <h1 className="text-2xl font-bold ml-4">Liste Des Addresse IP:</h1>
        <div>
          <a
            href="/i/p/address/new"
            className="inline-block bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded border border-blue-600"
          >
            Nouvelle Addresse
          </a>
        </div>
        <div className="mr-4">
          <SearchIPComponent />
        </div>
      </div>
      <div className="table-container rounded border-5">
        <table className="table w-full border-collapse">
          <thead>
            <tr>
              <th className="border pl-2 py-3">ID</th>
              <th className="border">Addresse</th>
              <th className="border">Masque</th>
              <th className="border">Actions</th>
            </tr>
          </thead>
          <tbody>
            {records.map((ip, index) => (
              <tr key={ip.id} className={`${index % 2 === 0 ? 'bg-gray-100' : 'bg-white'} border`}>
                <td className="border pl-4">{ip.id}</td>
                <td className="border pl-4">{ip.address}</td>
                <td className="border pl-4">{ip.mask}</td>
                <td className="flex items-center justify-center">
  <a
    href={`/i/p/address/${ip.id}`}
    className="text-blue-500 hover:text-blue-700"
  >
    <FontAwesomeIcon icon={faEye} size="lg" />
  </a>
  <a
    href={`/i/p/address/${ip.id}/edit`}
    className="ml-2 text-green-500 hover:text-green-700"
  >
    <FontAwesomeIcon icon={faEdit} size="lg" />
  </a>
  <button
    onClick={() => handlePingTest(ip.address)}
    className="ml-2"
  >
    <FontAwesomeIcon icon={faFlask} size="lg" />
  </button>
</td>

              </tr>
            ))}
            {ipaddresses.length === 0 && (
              <tr>
                <td colSpan="4" className="border px-4 py-2">
                  No records found
                </td>
              </tr>
            )}
          </tbody>
        </table>
        </div>
      <nav className="flex justify-center items-center mt-4">
  <ul className="pagination flex space-x-4">
    <li className="page-item">
      <a
        href="#"
        className="page-link px-3 py-2 border rounded text-blue-500 border-blue-500 hover:bg-blue-500 hover:text-white"
        onClick={() => handlePageChange(currentPage - 1)}
      >
        Prec
      </a>
    </li>
    {pageNumbers.map((pageNumber) => (
      <li
        className={`page-item ${currentPage === pageNumber ? 'active' : ''}`}
        key={pageNumber}
      >
        <a
          href="#"
          className={`page-link px-3 py-2 border rounded ${
            currentPage === pageNumber
              ? 'bg-blue-500 text-white'
              : 'border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white'
          }`}
          onClick={() => handlePageChange(pageNumber)}
        >
          {pageNumber}
        </a>
      </li>
    ))}
    <li className="page-item">
      <a
        href="#"
        className="page-link px-3 py-2 border rounded text-blue-500 border-blue-500 hover:bg-blue-500 hover:text-white"
        onClick={() => handlePageChange(currentPage + 1)}
      >
        Proc
      </a>
    </li>
  </ul>
</nav>

    </div>
    
  );
};

export default AIPAddressTable;

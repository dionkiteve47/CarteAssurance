import { Menu } from '@headlessui/react';
import { BiDotsHorizontalRounded } from 'react-icons/bi';
import SearchComponent from './SearchComponent';
import React, { useState } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEye, faEdit } from '@fortawesome/free-solid-svg-icons';

const UserTable = ({ users }) => {
  console.log('Number of users:', users.length);
  const [currentPage, setCurrentPage] = useState(1);
  const recordsPerPage = 10;
  const lastIndex = currentPage * recordsPerPage;
  const firstIndex = lastIndex - recordsPerPage;
  const records = users.slice(firstIndex, lastIndex);
  const totalPages = Math.ceil(users.length / recordsPerPage);
  const pageNumbers = [...Array(totalPages).keys()].map((num) => num + 1);
  const handlePageChange = (pageNumber) => {
    setCurrentPage(pageNumber);
  };
  return (
    <div className="pl-20 pr-20 py-10 bg-gray-100 min-h-screen" >
      <div className="flex justify-between items-center mb-4">
        <h1 className="text-2xl font-bold ml-4">Liste des utilisateurs:</h1>
        <div >
        <a
          href="/register"
          className="inline-block bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded border border-blue-600"
        >
          Ajouter Nouveau
        </a>
      </div>
        <div className="mr-4">
        <SearchComponent />
        </div>
      </div>
      <div className="table-container rounded shadow-lg" style={{ border: '1px solid #D1D5DB' }}>
  <table className="table w-full border-collapse">
        <thead>
          <tr>
            <th className="border pl-2 py-3">ID</th>
            <th className="border">Email</th>
            <th className="border ">Roles</th>
            <th className="border ">Cin</th>
            <th className="border ">Firstname</th>
            <th className="border ">Lastname</th>
            <th className="border ">Naissance</th>
            <th className="border ">Telephone</th>
            <th className="border ">Actions</th>
          </tr>
        </thead>
        <tbody>
          {records.map((user, index) => (
           <tr key={user.id} className={`${index % 2 === 0 ? 'bg-gray-100' : 'bg-white'} border`}>
              <td className="border pl-4">{user.id}</td>
              <td className="border pl-4 ">{user.email}</td>
              <td className="border pl-4 ">{user.roles ? user.roles.join(', ') : ''}</td>
              <td className="border pl-4 ">{user.Cin}</td>
              <td className="border pl-4 ">{user.firstname}</td>
              <td className="border pl-4 ">{user.lastname}</td>
              <td className="border pl-4 ">{user.Naissance ? user.Naissance : ''}</td>
              <td className="border pl-4 ">{user.Telephone}</td>
              
              
              <td className="border pl-4">
                <a
                  href={`/user/show/${user.id}`}
                  className="text-blue-500 hover:text-blue-700"
                >
                  {/* Replace with appropriate icon */}
                  <FontAwesomeIcon icon={faEye} size="lg"/>
                </a>
                <a
                  href={`/user/${user.id}/edit`}
                  className="ml-2 text-green-500 hover:text-green-700"
                >
                  {/* Replace with appropriate icon */}
                  <FontAwesomeIcon icon={faEdit} size="lg" />
                </a>
              
        </td>
            </tr>
          ))}
          {users.length === 0 && (
            <tr>
              <td colSpan="11" className="border px-4 py-2">No records found</td>
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
export default UserTable;

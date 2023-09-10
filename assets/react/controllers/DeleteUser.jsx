import React from 'react';

const DeleteUser= ({ userId }) => {
  const handleDelete = () => {
    if (window.confirm('Are you sure you want to delete this item?')) {
      // Perform the delete action
    }
  };

  return (
    <form
      method="post"
      action={`/user/delete/${userId}`}
      onSubmit={handleDelete}
      className="block px-4 py-2"
    >
      <input type="hidden" name="_token" value={`{{ csrf_token('delete' ~ user.id) }}`} />
      <button className="btn" type="submit">
        Delete
      </button>
    </form>
  );
};

export default DeleteUser;

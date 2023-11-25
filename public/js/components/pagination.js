
// Pagination component
function chunkArray(array, chunkSize=5) {
    const result = [];
    for (let i = 0; i < array.length; i += chunkSize) {
      result.push(array.slice(i, i + chunkSize));
    }
    return result;
  }
  // show page content
  function showPageContent(page, data) {
    updateBooks(data[page - 1]);
    showPagination(page, data);
   }
 
   function createRow(book) {
    const row = document.createElement('div');
    row.className = 'row';
    const col = document.createElement('div');
    col.className = 'col';
    const h2 = document.createElement('h2');
    h2.textContent = book.title;
    const showLink = document.createElement('a');
    showLink.href = `/books/${book.id}`;
    showLink.className = 'btn btn-success';
    showLink.textContent = 'Show';

    // check if user is logged in
    if (window.user) {
        const editLink = document.createElement('a');
        editLink.href = `/books/${book.id}/edit`;
        editLink.className = 'btn btn-primary';
        editLink.textContent = 'Edit';
        col.appendChild(editLink);
    }

    const authorP = document.createElement('p');
    authorP.textContent = `Author: ${book.authors.map(author => author.name).join(', ')}`;
    const publisherP = document.createElement('p');
    publisherP.textContent = `Publisher: ${book.publishers.map(publisher => publisher.name).join(', ')}`;

    col.appendChild(h2);
    col.appendChild(showLink);
    col.appendChild(authorP);
    col.appendChild(publisherP);
    row.appendChild(col);

    return row;
}

function updateBooks(data) {
    const container = document.getElementById('_books');
    container.innerHTML = '';

    data.forEach(book => {
        const row = createRow(book);
        container.appendChild(row);
    });
}
 // show pagination
 function showPagination(page, data) {
  const container = document.getElementById('_pagination');
  container.innerHTML = '';
  const row = document.createElement('div');
  row.className = 'row';
  for (let i = 1; i <= data.length; i++) {

    const col = document.createElement('div');
    col.className = 'col';
    const link = document.createElement('a');
    link.href = '#';
    link.textContent = i;

    // Add class Bootstrap 'btn'
    link.className = 'btn';

    if (i === page) {
      link.className += ' btn-primary text-white'; // Add class Bootstrap 'btn-primary' for current page
    } else {
      link.className += ' btn-light text-dark'; // Add class Bootstrap 'btn-light' for other pages
    }

    link.addEventListener('click', () => showPageContent(i, data));
    col.appendChild(link);
    row.appendChild(col);
  }  
  container.appendChild(row);
}

function sort(type) {
  const books = window.books;
  if (type === 'az') {
    books.sort((a, b) => a.title.localeCompare(b.title));
  }
  else if (type === 'za') {
    books.sort((a, b) => b.title.localeCompare(a.title));
  }
  else if (type === 'tm') {
    books.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
  }
  window.books = books;
  window.pages=chunkArray(window.books, 5);
  showPageContent(1, window.pages);
  showPagination(1, window.pages);
}

const BookController = {
  books: [],
  pages: [],
  page: 1,
  constructor(){
    this.books = window.books;
    this.pages = this.chunkArray(this.books, 5);
    this.showPageContent(1, this.pages);
    this.showPagination(1, this.pages);

  },
  chunkArray(array, chunkSize = 5) {
    const result = [];
    for (let i = 0; i < array.length; i += chunkSize) {
      result.push(array.slice(i, i + chunkSize));
    }
    return result;
  },
  showPageContent(page,data) {
    window.page = page;
    this.updateBooks(data[page - 1]);
    this.showPagination(page, data);
  },
  updateBooks(books) {
    const container = document.getElementById('_books');
    container.innerHTML = '';
    console.log(books);
    if (Array.isArray(books)) {
      books.forEach(book => {
        const row = this.createRow(book);
        container.appendChild(row);
      });
    } else {
      console.log('Books is not an array:', books);
    }
  },
  showPagination(page, data){
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

      link.addEventListener('click', () => this.showPageContent(i, data));
      col.appendChild(link);
      row.appendChild(col);
    }  
    container.appendChild(row);
  },
  sort(type){
    const books = window.books;
    if (type === 'az') { // sort by title a->z
      books.sort((a, b) => a.title.localeCompare(b.title));
    }
    else if (type === 'za') { // sort by title z->a
      books.sort((a, b) => b.title.localeCompare(a.title));
    }
    else if (type === 'tm') { // sort by created_at time
      books.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
    }
    window.books = books;
    window.pages = this.chunkArray(books, 5);
    this.showPageContent(1, window.pages);
    this.showPagination(1, window.pages);

  },
  showOneBook(id){
    const book = window.books.find(book => book.id === id);
    const container = document.getElementById('_books');
    container.innerHTML = '';
    const row = this.createRow(book, false);
    container.appendChild(row);
    // in section _pagination, show only one button 'Back' AND form delete with token if user is logged in
    const pagination = document.getElementById('_pagination');
    pagination.innerHTML = '';
    const row2 = document.createElement('div');
    row2.className = 'row';
    const col = document.createElement('div');
    col.className = 'col';  
    const back = document.createElement('a');
    back.href = '#';
    back.textContent = 'Back';
    back.className = 'btn btn-primary text-white';
    back.addEventListener('click', () => this.showPageContent(window.page, window.pages));
    col.appendChild(back);
    row2.appendChild(col);
    pagination.appendChild(row2);
    // check if user is logged in
    if (window.user) {
      const col2 = document.createElement('div');
      col2.className = 'col';  
      // add button 'Edit'
      const editLink = document.createElement('a');
      editLink.href = `/books/${book.id}/edit`;
      editLink.className = 'btn btn-primary';
      editLink.textContent = 'Edit';
      col2.appendChild(editLink);
      // create form delete
      const deleteBtn = document.createElement('button');
      deleteBtn.type = 'button';
      deleteBtn.className = 'btn btn-danger';
      deleteBtn.textContent = 'Delete';
      deleteBtn.addEventListener('click', () => this.deleteBook(id));
      col2.appendChild(deleteBtn);
      row2.appendChild(col2);
    }
  },
  createRow(book, showButton = true) {
    const row = document.createElement('div');
    row.className = 'row';
    const col = document.createElement('div');
    col.className = 'col';
    const h2 = document.createElement('h2');
    h2.textContent = book.title;
    if (showButton) {
      const showLink = document.createElement('a');
      showLink.href = '#';
      showLink.addEventListener('click', () => this.showOneBook(book.id));
      showLink.className = 'btn btn-success';
      showLink.textContent = 'Show';
      col.appendChild(showLink);
      if (window.user) {
        const editLink = document.createElement('a');
        editLink.href = `/books/${book.id}/edit`;
        editLink.className = 'btn btn-primary';
        editLink.textContent = 'Edit';
        col.appendChild(editLink);
      }
    }
    const authorP = this.createAuthorsList(book.authors);
    const publisherP = this.createPublishersList(book.publishers);
    col.appendChild(h2);
    col.appendChild(authorP);
    col.appendChild(publisherP);
    row.appendChild(col);
      return row;
  },
  showBooksByAuthor(id){
    const booksByAuthor = window.books.filter(book => book.authors.some(author => author.id === id));
    window.pages = this.chunkArray(booksByAuthor, 5);
    this.showPageContent(1, window.pages);
    this.showPagination(1, window.pages);
  },
  showBooksByPublisher(id){
    const booksByPublisher = window.books.filter(book => book.publishers.some(publisher => publisher.id === id));
    window.pages = this.chunkArray(booksByPublisher, 5);
    this.showPageContent(1, window.pages);
    this.showPagination(1, window.pages);
  },
  deleteBook(id) {
    fetch(`/books/${id}`, {
      method: 'DELETE',
      headers: {
          'X-CSRF-TOKEN': window.csrfToken,
          'Content-Type': 'application/json',
      },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        // success message
        console.log(data);
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
    });
  },
  createAuthorsList(authors){
    const ul = document.createElement('ul');
      authors.forEach(author => {
      const li = document.createElement('li');
      const link = document.createElement('a');
      link.href = '#'; 
      link.textContent = author.name;
      link.addEventListener('click', () => this.showBooksByAuthor(author.id));
      li.appendChild(link);
      ul.appendChild(li);
      });
  return ul;
  },
  createPublishersList(publishers){
    const ul = document.createElement('ul');
      publishers.forEach(publisher => {
      const li = document.createElement('li');
      const link = document.createElement('a');
      link.href = '#'; 
      link.textContent = publisher.name;
      link.addEventListener('click', () => this.showBooksByPublisher(publisher.id));
      li.appendChild(link);
      ul.appendChild(li);
      });
  return ul;
  },
  searchBooks(api_url) {
    const search = document.getElementById('search').value;
    //console.log(search);
    // form post data
    const formData = new FormData();
    formData.append('search', search);
    // ajax request
    fetch(api_url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': window.csrfToken,
      },
      body: formData,
    })
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json(); // change to JSON
    })
    .then(data => {
      //console.log(data);
      if (data.length > 0) {
      window.books = data;
      window.pages = this.chunkArray(window.books, 5);
      this.showPageContent(1, window.pages);
      this.showPagination(1, window.pages);
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
  }
}






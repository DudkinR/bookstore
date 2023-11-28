<?php
namespace App\Services;

use App\Models\Book;

class BookService
{
    public function getOrCreateBook($title, $author, $publisher)
    {
        $book = Book::where('title', $title)->first();

        if ($book) {
            return $book;
        } else {
            $newBook = new Book();
            $newBook->title = $title;
            $newBook->save();
            $newBook->authors()->attach($author);
            $newBook->publishers()->attach($publisher);
            return $newBook;
        }
    }
}

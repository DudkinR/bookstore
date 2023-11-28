<?php
namespace App\Services;

use App\Models\Author;

class AuthorService
{
    public function getOrCreateAuthor($name)
    {
        $author = Author::where('name', $name)->first();

        if ($author) {
            return $author;
        } else {
            $newAuthor = new Author();
            $newAuthor->name = $name;
            $newAuthor->save();
            return $newAuthor;
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    // table
    protected $table = 'books';
    // fillable
    protected $fillable = [
        'title',
          ];
    // relationships
    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_author');
    }
    public function publishers()
    {
        return $this->belongsToMany(Publisher::class, 'book_publisher');
    }
    public function loadAuthorsAndPublishers()
    {
        return $this->with('authors', 'publishers')->get();
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;
    // table
    protected $table = 'authors';
    // fillable
    protected $fillable = [
        'name',
          ];
    // relationships
    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_author');
    }
}

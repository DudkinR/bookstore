<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        \App\Models\Book::factory()->count(10)->create();
        // author 
        \App\Models\Author::factory()->count(10)->create();
        // publisher
        \App\Models\Publisher::factory()->count(10)->create();
        // add attach
        $books = \App\Models\Book::all();
        $authors = \App\Models\Author::all();
        $publishers = \App\Models\Publisher::all();
        foreach ($books as $book) {
            $book->authors()->attach(
                $authors->random(rand(1, 3))->pluck('id')->toArray()
            );
            $book->publishers()->attach(
                $publishers->random(rand(1, 3))->pluck('id')->toArray()
            );
        }

    }
}

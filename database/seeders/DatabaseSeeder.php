<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Author::factory()->count(20)->create();
        Publisher::factory()->count(5)->create();
        Book::factory()->count(100)->create();

        Book::all()->each(function ($book) {
            $book->authors()->attach(
                Author::all()->random(rand(1, 3))->pluck('id')->toArray()
            );
            $book->publishers()->attach(
                Publisher::all()->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}

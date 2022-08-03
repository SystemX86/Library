<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\AuthorBook;
use App\Models\Book;
use App\Models\BookPublisher;
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
        Author::factory(20)->create();
        Publisher::factory(10)->create();
        Book::factory(100)->create();
        AuthorBook::factory(100)->create();
        BookPublisher::factory(100)->create();
    }
}

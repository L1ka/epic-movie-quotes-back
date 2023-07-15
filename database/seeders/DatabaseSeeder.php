<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Comment;
use Illuminate\Database\Seeder;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Quote;
use App\Models\User;
use Database\Factories\QuoteUserFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        // Movie::factory(5)->create();
        // Quote::factory(10)->create();
        // Comment::factory(20)->create();
        // QuoteUserFactory::factory(5)->create();

        $genres = [
            'Action',
            'Comedy',
            'Drama',
            'Horror',
            'Thriller',
            'Adventure',
            'Fantasy',
            'Musicals',
            'Romance',
            'Science Fiction',
            'Sports'
        ];

        foreach ($genres as $genre) {
            Genre::create(['genre' => $genre]);
        }
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Genre;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();
       

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

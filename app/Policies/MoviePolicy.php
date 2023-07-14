<?php

namespace App\Policies;

use App\Models\Movie;
use App\Models\User;

class MoviePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function store(User $user, Movie $movie): bool
    {
       return $user->id === $movie->user_id;
    }

    public function create(User $user): bool
    {
        return User::where('id', $user->id)->exists();
    }

    public function update(User $user, Movie $movie): bool
    {
       return $user->id === $movie->user_id;
    }

    public function delete(User $user, Movie $movie): bool
    {
        return $user->id === $movie->user_id;
    }
}

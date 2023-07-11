<?php

namespace App\Policies;

use App\Models\Movie;
use App\Models\Quote;
use App\Models\User;


class QuotePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }


    public function update(User $user, Quote $quote): bool
    {
        return $user->id === $quote->user_id;
    }

    public function delete(User $user, Quote $quote): bool
    {
        return $user->id === $quote->user_id;
    }


}

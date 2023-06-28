<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $with = ['movie'];


    public function movie(): BelongsTo
    {
      return $this->BelongsTo(Movie::class);
    }



    public function users(): BelongsTo
    {
      return $this->BelongsTo(User::class);
    }


    public function comments(): HasMany
    {
      return $this->hasMany(Comment::class);
    }


    public function likes(): HasMany
    {
      return $this->hasMany(Like::class);
    }
}

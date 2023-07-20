<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Quote extends Model
{
	use HasFactory;

	protected $guarded = ['id'];

	protected $with = ['movie'];

	public function movie(): BelongsTo
	{
		return $this->BelongsTo(Movie::class);
	}

	public function user(): BelongsTo
	{
		return $this->BelongsTo(User::class);
	}

	public function likers(): BelongsToMany
	{
		return $this->belongsToMany(User::class, 'quote_user')
		->withTimestamps();
	}

	public function comments(): HasMany
	{
		return $this->hasMany(Comment::class);
	}

	public function notifications(): MorphMany
	{
		return $this->morphMany(Notification::class, 'notifiable');
	}

	public function scopeFilterQuote($query, $request): void
	{
		$searchValue = trim(str_replace('#', '', $request->search));

		$query->where(function ($query) use ($searchValue) {
			$query->where('quote->en', 'like', '%' . $searchValue . '%')
				->orWhere('quote->ka', 'like', '%' . $searchValue . '%');
		});
	}

	public function scopeFilterMovie($query, $request): void
	{
		$searchValue = trim(str_replace('@', '', $request->search));

		$query->whereHas('movie', function ($query) use ($searchValue) {
			$query->where('title->en', 'like', '%' . $searchValue . '%')
				->orWhere('title->ka', 'like', '%' . $searchValue . '%');
		});
	}
}

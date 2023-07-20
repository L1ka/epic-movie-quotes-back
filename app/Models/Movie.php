<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\belongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
	use HasFactory;

	protected $guarded = ['id'];

	public function genres(): belongsToMany
	{
		return $this->belongsToMany(Genre::class, 'genre_movie');
	}

	public function users(): BelongsTo
	{
		return $this->BelongsTo(User::class);
	}

	public function quotes(): hasMany
	{
		return $this->hasMany(Quote::class);
	}

	public function scopeFilter($query, $request): void
	{
		$query->where(function ($query) use ($request) {
			$query->where('title->en', 'like', '%' . $request->search . '%')
				->orWhere('title->ka', 'like', '%' . $request->search . '%');
		})
		->orderBy('id', 'desc');
	}
}

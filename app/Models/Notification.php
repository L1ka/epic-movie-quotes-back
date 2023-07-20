<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
	use HasFactory;

	protected $guarded = ['id'];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function quote()
	{
		return $this->belongsTo(Quote::class);
	}

	public function notifiable(): MorphTo
	{
		return $this->morphTo();
	}
}

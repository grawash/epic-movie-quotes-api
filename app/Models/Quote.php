<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
	use HasFactory;

	protected $guarded = [];

	public function movies(): BelongsTo
	{
		return $this->belongsTo(Movie::class, 'movie_genres');
	}

	public function comments(): HasMany
	{
		return $this->hasMany(Comment::class);
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Movie extends Model
{
	use HasFactory, HasTranslations;

	protected $guarded = [];

	public function genres(): BelongsToMany
	{
		return $this->belongsToMany(Genre::class, 'movie_genre');
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function quotes(): HasMany
	{
		return $this->hasMany(Quote::class);
	}

	public $translatable = ['title', 'director', 'description'];
}

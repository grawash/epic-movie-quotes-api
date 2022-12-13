<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class notification extends Model
{
	use HasFactory;

	protected $guarded = [];

	public function sender(): BelongsTo
	{
		return $this->belongsTo(User::class, 'sender_id');
	}

	public function reciever(): BelongsTo
	{
		return $this->belongsTo(User::class, 'reciever_id');
	}

	public function quote(): BelongsTo
	{
		return $this->belongsTo(Quote::class);
	}
}

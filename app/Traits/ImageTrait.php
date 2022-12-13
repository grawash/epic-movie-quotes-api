<?php

namespace App\Traits;

trait ImageTrait
{
	public function verifyAndUpload(object $image, string $directory)
	{
		$imageName = time() . '.' . $image->getClientOriginalExtension();
		$path = $image->storeAs('public/' . $directory, $imageName);
		$path = config('auth.back_end_full_domain') . str_replace('public', 'storage', $path);
		return $path;
	}
}

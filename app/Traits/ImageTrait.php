<?php

namespace App\Traits;

trait ImageTrait
{
	public function verifyAndUpload(object $image, string $directory)
	{
		$imageName = time() . '.' . $image->getClientOriginalExtension();
		$path = $image->storeAs('public/' . $directory, $imageName);
		return $path;
	}
}

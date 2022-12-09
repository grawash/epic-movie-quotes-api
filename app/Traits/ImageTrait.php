<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ImageTrait
{
	/**
	 * @param Request $request
	 *
	 * @return $this|false|string
	 */
	public function verifyAndUpload($image, $directory)
	{
		$imageName = time() . '.' . $image->getClientOriginalExtension();
		$path = $image->storeAs('public/' . $directory, $imageName);
		return $path;
	}
}

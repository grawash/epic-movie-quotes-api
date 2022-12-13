<?php

// app/Extensions/MongoUserProvider.php

namespace App\Extensions;

use App\Models\User;
use Exception;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class MongoUserProvider implements UserProvider
{
	private $model;

	public function __construct(User $user)
	{
		$this->model = $user;
	}

public function retrieveByCredentials(array $credentials)
{
	if (empty($credentials))
	{
		return;
	}

	try
	{
		if (!request()->cookie('access_token') && !request()->header('Authorization'))
		{
			return null;
		}

		$decoded = JWT::decode(
			request()->cookie('access_token') ?? substr(request()->header('Authorization'), 7),
			new Key(config('auth.jwt_secret'), 'HS256')
		);

		return User::find($decoded->uid);
	}
	catch (Exception $e)
	{
		return;
	}
}

public function validateCredentials(Authenticatable $user, array $credentials)
{
	return $credentials['username'] == $user->getAuthIdentifier() &&
  md5($credentials['password']) == $user->getAuthPassword();
}

public function retrieveById($identifier)
{
}

public function retrieveByToken($identifier, $token)
{
}

public function updateRememberToken(Authenticatable $user, $token)
{
}
}

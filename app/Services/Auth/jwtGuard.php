<?php

namespace App\Services\Auth;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Carbon\Carbon;
use Exception;
use Firebase\JWT\Key;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

class jwtGuard implements Guard
{
	private $request;

	private $provider;

	private $user;

	public function __construct(UserProvider $provider, Request $request)
	{
		$this->request = $request;
		$this->provider = $provider;
		$this->user = null;
	}

	public function check()
	{
		try
		{
			if (request()->cookie('access_token'))
			{
				$token = request()->cookie('access_token');
			}
			if (request()->header('Authorization') > 7)
			{
				$token = substr(request()->header('Authorization'), 7);
			}
			if (!isset($token))
			{
				return false;
			}
			$decoded = JWT::decode(
				$token,
				new Key(
					config('auth.jwt_secret'),
					'HS256'
				)
			);
			if ($decoded->exp > Carbon::now()->timestamp)
			{
				return true;
			}
			return false;
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	public function user()
	{
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
			return null;
		}
	}

	public function guest()
	{
		return !isset($this->user);
	}

	public function id()
	{
		if (isset($this->user))
		{
			return $this->user->getAuthIdentifier();
		}
	}

	public function hasUser()
	{
	}

	public function validate(array $credentials = [])
	{
		if (!isset($credentials['username']) || empty($credentials['username']) || !isset($credentials['password']) || empty($credentials['password']))
		{
			return false;
		}

		$user = $this->provider->retrieveById($credentials['username']);

		if (!isset($user))
		{
			return false;
		}
		if ($this->provider->validateCredentials($user, $credentials))
		{
			$this->setUser($user);
			return true;
		}
		else
		{
			return false;
		}
	}

	public function setUser(Authenticatable $user)
	{
		$this->user = $user;
	}
}

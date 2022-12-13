<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The model to policy mappings for the application.
	 *
	 * @var array<class-string, class-string>
	 */
	protected $policies = [
		// 'App\Models\Model' => 'App\Policies\ModelPolicy',
	];

	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerPolicies();

		Auth::viaRequest('jwt', function () {
			try
			{
				if (!request()->cookie('access_token') && !request()->header('Authorization'))
				{
					throw null;
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
		});
	}
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Str;

class GithubController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * @throws Exception
     */
    public function callback()
    {
        try {
            $user = Socialite::driver('github')->user();

            $git_user = User::updateOrCreate([
                'github_id' => $user->id
            ], [
                'name' => $user->name,
                'nickname' => $user->nickname,
                'email' => $user->email,
                'github_token' => $user->token,
                'auth_type' => 'GITHUB',
                'password' => Hash::make(Str::random(10))
            ]);
            Auth::login($git_user);

            return redirect(route('home.index'));
        } catch (Exception $e) {
            throw new Exception("Error occured while trying to authorize via github...".$e->getMessage());
        }
    }
}

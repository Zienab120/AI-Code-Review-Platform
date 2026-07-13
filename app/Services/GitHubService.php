<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class GitHubService
{
    public function authenticate()
    {
        return Socialite::driver('github')->scopes(['read:user', 'repo'])->redirect();
    }

    public function callback($userID)
    {
        $gitHubUser = Socialite::driver('github')->user();
        $user = User::findOrFail($userID);
        $user->update([
            'github_id' => $gitHubUser->getId(),
            'github_token' => $gitHubUser->token,
            'github_refresh_token' => $gitHubUser->refreshToken,
            'github_expires_in' => $gitHubUser->expiresIn ? Carbon::now()->addSeconds($gitHubUser->expiresIn) : null,
        ]);
    }

    public function getRepositories()
    {
        $user = Auth::user();
        $response = Http::withToken($user->token)
            ->withHeaders([
                'Accept' => 'application/vnd.github+json',
            ])
            ->get('https://api.github.com/user/repos', [
                'sort' => 'updated',
                'per_page' => 100,
            ]);

        if ($response->successful()) return $response->json();
        return [];
    }
}

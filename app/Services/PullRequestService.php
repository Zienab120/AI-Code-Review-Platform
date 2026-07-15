<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PullRequsetService
{
    protected function client($user)
    {
        return Http::withToken($user->github_token)
            ->withHeaders(['Accept' => 'application/vnd.github+json'])
            ->baseUrl('https://api.github.com');
    }

    public function listPullRequests($user, $owner, $repo, $state = 'open')
    {
        $response = $this->client($user)->get("/repos/{$owner}/{$repo}/pulls", [
            'state' => $state,
        ]);
        return $response->successful() ? $response->json() : [];
    }

    public function getPullRequest($user, $owner, $repo, $number)
    {
        $response = $this->client($user)->get("/repos/{$owner}/{$repo}/pulls/{$number}");
        return $response->successful() ? $response->json() : null;
    }

    public function submitReview($user, $owner, $repo, $number, $event, $body)
    {
        // Event types can be: APPROVE, REQUEST_CHANGES, or COMMENT
        $response = $this->client($user)->post("/repos/{$owner}/{$repo}/pulls/{$number}/reviews", [
            'event' => $event,
            'body'  => $body,
        ]);
        return $response->successful() ? $response->json() : null;
    }

    public function closePullRequest($user, $owner, $repo, $number)
    {
        $response = $this->client($user)->patch("/repos/{$owner}/{$repo}/pulls/{$number}", [
            'state' => 'closed',
        ]);
        return $response->successful() ? $response->json() : null;
    }

    public function retryFailedWorkflow($user, $owner, $repo, $branch)
    {
        // Step 1: Find the latest workflow run on this specific branch
        $runsResponse = $this->client($user)->get("/repos/{$owner}/{$repo}/actions/runs", [
            'branch' => $branch,
            'per_page' => 1
        ]);

        if ($runsResponse->successful() && !empty($runsResponse->json()['workflow_runs'])) {
            $latestRunId = $runsResponse->json()['workflow_runs'][0]['id'];

            // Step 2: Trigger a re-run of this action workflow
            $retryResponse = $this->client($user)->post("/repos/{$owner}/{$repo}/actions/runs/{$latestRunId}/rerun");
            return $retryResponse->successful();
        }

        return false;
    }
}

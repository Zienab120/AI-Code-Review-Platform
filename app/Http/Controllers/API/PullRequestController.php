<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\PullRequsetService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PullRequestController extends Controller
{
    use ApiResponseTrait;
    public function __construct(protected PullRequsetService $pullRequestService) {}

    /**
     * List pull requests
     */
    public function index(Request $request, $owner, $repo)
    {
        $state = $request->query('state', 'open');
        $pullRequests = $this->pullRequestService->listPullRequests(Auth::user(), $owner, $repo, $state);

        return response()->json($pullRequests);
    }

    /**
     * Get a single pull request details
     */
    public function show($owner, $repo, $number)
    {
        $pr = $this->pullRequestService->getPullRequest(Auth::user(), $owner, $repo, $number);

        if (!$pr) {
            return response()->json(['error' => 'Pull Request not found'], 404);
        }

        return response()->json($pr);
    }

    /**
     * Submit an approval or comment review on a PR
     */
    public function review(Request $request, $owner, $repo, $number)
    {
        $request->validate([
            'event' => 'required|string|in:APPROVE,REQUEST_CHANGES,COMMENT',
            'body'  => 'required|string|min:3',
        ]);

        $review = $this->pullRequestService->submitReview(
            Auth::user(),
            $owner,
            $repo,
            $number,
            $request->event,
            $request->body
        );

        if (!$review) {
            return response()->json(['error' => 'Failed to submit review'], 400);
        }

        return response()->json(['message' => 'Review submitted successfully', 'data' => $review]);
    }

    /**
     * Retry failed Actions workflows linked to this PR's head branch
     */
    public function retry(Request $request, $owner, $repo, $number)
    {
        // First, fetch the PR to find what branch it is on
        $pr = $this->pullRequestService->getPullRequest(Auth::user(), $owner, $repo, $number);

        if (!$pr) {
            return response()->json(['error' => 'Pull Request not found'], 404);
        }

        $branchName = $pr['head']['ref'] ?? null;

        if (!$branchName) {
            return response()->json(['error' => 'Could not determine branch name'], 422);
        }

        $success = $this->pullRequestService->retryFailedWorkflow(Auth::user(), $owner, $repo, $branchName);

        if (!$success) {
            return response()->json(['error' => 'Could not trigger workflow rerun'], 400);
        }

        return response()->json(['message' => 'Workflow re-run successfully triggered']);
    }

    /**
     * Cancel (close) a PR
     */
    public function cancel($owner, $repo, $number)
    {
        $pr = $this->pullRequestService->closePullRequest(Auth::user(), $owner, $repo, $number);

        if (!$pr) {
            return response()->json(['error' => 'Failed to close Pull Request'], 400);
        }

        return response()->json(['message' => 'Pull request closed successfully', 'data' => $pr]);
    }
}

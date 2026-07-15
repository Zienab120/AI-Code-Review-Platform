<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ReviewService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use ApiResponseTrait;
    public function __construct(protected ReviewService $reviewService) {}

    public function index($pullRequestID)
    {
        $data = $this->reviewService->getLatestReviews($pullRequestID);
        return $this->successResponse($data);
    }

    public function show($reviewID)
    {
        $data = $this->reviewService->showReview($reviewID);
        return $this->successResponse($data);
    }

    public function store(Request $request, $pullRequestID)
    {
        $this->reviewService->storePullRequestReview($pullRequestID, $request->all());
        return $this->successResponse(null, 'Review submitted successfully.');
    }

    public function update(Request $request, $reviewID)
    {
        $this->reviewService->updatePullRequestReview($reviewID, $request->all());
        return $this->successResponse(null, 'Review updated successfully.');
    }

    // public function calculateScore(){}
}

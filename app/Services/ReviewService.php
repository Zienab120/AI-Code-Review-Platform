<?php 

namespace App\Services;

use App\Models\Review;

class ReviewService
{
    public function showReview($reviewID)
    {
        return Review::findOrFail($reviewID);
    }

    public function getLatestReviews($pullRequestID)
    {
        return Review::where('pull_request_id', $pullRequestID)->latest()->get();
    }

    public function storePullRequestReview($pullRequestID, $data)
    {
        Review::create([
            'pull_request_id' => $pullRequestID,
            'score' => $data['score'],
            'status' => $data['status'],
            'summary' => $data['summary'],
            'ai_model' => $data['ai_model'],
            'tokens_used' => $data['tokens_used'],
            'review_duration' => $data['review_duration'],
        ]);
    }

    public function updatePullRequestReview($reviewID, $data)
    {
        $review = Review::findOrFail($reviewID);
        $review->update($data);
    }

    // public function calculateScore()
}
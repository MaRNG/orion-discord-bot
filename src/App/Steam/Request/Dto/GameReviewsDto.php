<?php

namespace App\Steam\Request\Dto;

class GameReviewsDto
{
    public function __construct(
        public readonly int $positiveReviews,
        public readonly int $negativeReviews,
        public readonly int $totalReviews,
    )
    {
    }
}
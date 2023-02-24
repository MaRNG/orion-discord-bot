<?php

namespace App\Util;

class GameReviewsCalculator
{
    public static function calculatePositivePercent(int $totalReviews, int $positiveReviews): int
    {
        if ($positiveReviews === 0)
        {
            return 0;
        }

        return (int)round(($positiveReviews / $totalReviews) * 100);
    }

    public static function getEmojiByPositivePercent(int $percentage): string
    {
        return match (true) {
            $percentage >= 90 => ':sunglasses:',
            $percentage >= 70 => ':smiley:',
            $percentage >= 50 => ':slight_smile:',
            $percentage >= 30 => ':yawning_face:',
            $percentage >= 10 => ':clown:',
            default => ':poop:',
        };
    }
}
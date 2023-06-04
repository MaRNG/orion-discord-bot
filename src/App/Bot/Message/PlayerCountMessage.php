<?php

namespace App\Bot\Message;

use App\Steam\Request\Dto\GameDetailDto;
use App\Steam\Request\Dto\GamePlayerCountDto;
use App\Steam\Request\Dto\GameReviewsDto;
use Discord\Builders\MessageBuilder;

class PlayerCountMessage
{
    public function __construct(
        public readonly MessageBuilder $messageBuilder,
        public readonly ?GamePlayerCountDto $gamePlayerCountDto,
        public readonly ?GameDetailDto $gameDetailDto,
        public readonly ?GameReviewsDto $gameReviewsDto
    )
    {
    }
}
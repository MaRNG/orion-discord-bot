<?php

namespace App\Model\Database\Entity;

class LogStatistic implements IEntity
{
    private ?int $id = null;
    private ?string $user_id = null;
    private ?string $username = null;
    private ?string $channel_id = null;
    private ?string $channel_name = null;
    private ?string $option_name = null;
    private ?string $option_value = null;
    private ?string $action = null;
    private ?string $message = null;
    private ?int $response_status_code = null;
    private ?string $found_game_name = null;
    private ?int $found_game_player_count = null;
    private ?int $found_game_player_reviews_positive = null;
    private int $found_too_many_games = 0;
    private int $selected_game = 0;
    private \DateTime $time;

    public function __construct()
    {
        $this->time = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     * @return LogStatistic
     */
    public function setUsername(?string $username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string|null $action
     * @return LogStatistic
     */
    public function setAction(?string $action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return string|null
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param string|null $user_id
     * @return LogStatistic
     */
    public function setUserId(?string $user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getChannelId()
    {
        return $this->channel_id;
    }

    /**
     * @param string|null $channel_id
     * @return LogStatistic
     */
    public function setChannelId(?string $channel_id)
    {
        $this->channel_id = $channel_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getChannelName()
    {
        return $this->channel_name;
    }

    /**
     * @param string|null $channel_name
     * @return LogStatistic
     */
    public function setChannelName(?string $channel_name)
    {
        $this->channel_name = $channel_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     * @return LogStatistic
     */
    public function setMessage(?string $message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOptionName()
    {
        return $this->option_name;
    }

    /**
     * @param string|null $option_name
     * @return LogStatistic
     */
    public function setOptionName(?string $option_name)
    {
        $this->option_name = $option_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOptionValue()
    {
        return $this->option_value;
    }

    /**
     * @param string|null $option_value
     * @return LogStatistic
     */
    public function setOptionValue(?string $option_value)
    {
        $this->option_value = $option_value;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getResponseStatusCode()
    {
        return $this->response_status_code;
    }

    /**
     * @param int|null $response_status_code
     * @return LogStatistic
     */
    public function setResponseStatusCode(?int $response_status_code)
    {
        $this->response_status_code = $response_status_code;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFoundGameName()
    {
        return $this->found_game_name;
    }

    /**
     * @param string|null $found_game_name
     * @return LogStatistic
     */
    public function setFoundGameName(?string $found_game_name)
    {
        $this->found_game_name = $found_game_name;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFoundGamePlayerCount()
    {
        return $this->found_game_player_count;
    }

    /**
     * @param int|null $found_game_player_count
     * @return LogStatistic
     */
    public function setFoundGamePlayerCount(?int $found_game_player_count)
    {
        $this->found_game_player_count = $found_game_player_count;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFoundGamePlayerReviewsPositive()
    {
        return $this->found_game_player_reviews_positive;
    }

    /**
     * @param int|null $found_game_player_reviews_positive
     * @return LogStatistic
     */
    public function setFoundGamePlayerReviewsPositive(?int $found_game_player_reviews_positive)
    {
        $this->found_game_player_reviews_positive = $found_game_player_reviews_positive;
        return $this;
    }

    /**
     * @return int
     */
    public function getFoundTooManyGames()
    {
        return $this->found_too_many_games;
    }

    /**
     * @param int $found_too_many_games
     * @return LogStatistic
     */
    public function setFoundTooManyGames(int $found_too_many_games)
    {
        $this->found_too_many_games = $found_too_many_games;
        return $this;
    }

    /**
     * @return int
     */
    public function getSelectedGame()
    {
        return $this->selected_game;
    }

    /**
     * @param int $selected_game
     * @return LogStatistic
     */
    public function setSelectedGame(int $selected_game)
    {
        $this->selected_game = $selected_game;
        return $this;
    }
}
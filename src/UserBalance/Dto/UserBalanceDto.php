<?php

namespace app\src\UserBalance\Dto;

use app\src\UserBalance\Form\UserBalanceForm;

class UserBalanceDto
{
    private int $userId;
    private float $balance;

    /**
     * @param int $userId
     * @param float $balance
     */
    public function __construct(int $userId, float $balance)
    {
        $this->userId = $userId;
        $this->balance = $balance;
    }

    public static function formUpdate(UserBalanceForm $request): UserBalanceDto
    {
        return new UserBalanceDto(
            $request->userId,
            $request->balance
        );
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }
}
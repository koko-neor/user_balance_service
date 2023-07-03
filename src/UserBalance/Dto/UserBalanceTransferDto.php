<?php

namespace app\src\UserBalance\Dto;

use app\src\UserBalance\Form\UserBalanceTransferForm;

class UserBalanceTransferDto
{
    private int $fromUserId;
    private int $toUserId;
    private float $balance;

    /**
     * @param int $fromUserId
     * @param int $toUserId
     * @param float $balance
     */
    public function __construct(int $fromUserId, int $toUserId, float $balance)
    {
        $this->fromUserId = $fromUserId;
        $this->toUserId = $toUserId;
        $this->balance = $balance;
    }

    public static function formUpdate(UserBalanceTransferForm $request): UserBalanceTransferDto
    {
        return new UserBalanceTransferDto(
            $request->fromUserId,
            $request->toUserId,
            $request->balance
        );
    }

    /**
     * @return int
     */
    public function getFromUserId(): int
    {
        return $this->fromUserId;
    }

    /**
     * @return int
     */
    public function getToUserId(): int
    {
        return $this->toUserId;
    }

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }
}
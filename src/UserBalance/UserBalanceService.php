<?php

namespace app\src\UserBalance;

use app\src\UserBalance\Dto\ResultDto;
use app\src\UserBalance\Dto\UserBalanceTransferDto;
use app\src\UserBalance\Dto\UserBalanceDto;
use app\src\UserBalance\Repository\UserBalanceRepository;

class UserBalanceService
{
    private UserBalanceRepository $balanceRepository;

    public function __construct(UserBalanceRepository $balanceRepository)
    {
        $this->balanceRepository = $balanceRepository;
    }

    public function handleIncreaseBalance(UserBalanceDto $dto): ResultDto
    {
        try {
            $userBalance = $this->balanceRepository->get($dto->getUserId());

            if (!isset($userBalance)) {
                $this->balanceRepository->add($dto);
            } else {
                $balance = bcadd($userBalance->balance, $dto->getBalance(), 4);
                $this->balanceRepository->save($dto->getUserId(), $balance);
            }

            return new ResultDto('Balance increased successfully', 200);
        } catch (\Throwable $exception) {
            return new ResultDto($exception->getMessage(), 500);
        }
    }

    public function handleDecreaseBalance(UserBalanceDto $dto): ResultDto
    {
        try {
            $userBalance = $this->balanceRepository->get($dto->getUserId());

            if (!$userBalance) {
                return new ResultDto('User balance not found', 404);
            }

            $updatedBalance = bcsub($userBalance->balance, $dto->getBalance(), 4);

            if ($updatedBalance < 0) {
                return new ResultDto('Insufficient balance', 400);
            }

            $this->balanceRepository->save($dto->getUserId(), $updatedBalance);

            return new ResultDto('Balance decrease successfully', 200);

        } catch (\Throwable $exception) {
            return new ResultDto($exception->getMessage(), 500);
        }
    }

    public function handleTransferBalance(UserBalanceTransferDto $dto): ResultDto
    {
        try {
            $fromUserBalance = $this->balanceRepository->get($dto->getFromUserId());
            $toUserBalance = $this->balanceRepository->get($dto->getToUserId());

            if (!$fromUserBalance || !$toUserBalance) {
                return new ResultDto('User balance not found', 404);
            }

            if ($fromUserBalance->balance < $dto->getBalance()) {
                return new ResultDto('Insufficient balance', 400);
            }

            $fromUserBalanceUpdated = bcsub($fromUserBalance->balance, $dto->getBalance(), 4);
            $toUserBalanceUpdated = bcadd($toUserBalance->balance, $dto->getBalance(), 4);

            $this->balanceRepository->save($dto->getFromUserId(), $fromUserBalanceUpdated);
            $this->balanceRepository->save($dto->getToUserId(), $toUserBalanceUpdated);

            return new ResultDto('Balance transfer completed successfully', 200);

        } catch (\Throwable $exception) {
            return new ResultDto($exception->getMessage(), 500);
        }
    }

    public function getBalance(int $userId): ResultDto
    {
        $balance = $this->balanceRepository->get($userId);

        if ($balance === null) {
            return new ResultDto('User balance not found', 404);
        }

        return new ResultDto('Current user balance: ' . $balance->balance, 200);
    }
}
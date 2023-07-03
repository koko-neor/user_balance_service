<?php

namespace app\src\UserBalance\Repository;

use app\models\UserBalance;
use app\src\UserBalance\Dto\UserBalanceDto;

interface UserBalanceRepositoryInterface
{
    public function get(int $userId): ?UserBalance;
    public function add(UserBalanceDto $dto): void;
    public function save(int $userId, float $balance): void;
}

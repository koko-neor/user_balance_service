<?php

namespace app\src\UserBalance\Repository;

use app\models\UserBalance;
use app\src\UserBalance\Dto\UserBalanceDto;
use yii\db\ActiveRecord;
use yii\db\Connection;
use Exception;
use yii\db\Query;

class UserBalanceRepository implements UserBalanceRepositoryInterface
{
    /**
     * @param int $userId
     * @return ActiveRecord|array|null
     */
    public function get(int $userId): ?UserBalance
    {
        return UserBalance::find()->select('balance')->where(['user_id' => $userId])->one();
    }

    /**
     * @throws Exception|\Throwable
     */
    public function add(UserBalanceDto $dto): void
    {
        \Yii::$app->db->transaction(function () use ($dto) {
            \Yii::$app->db->createCommand()->insert('{{user_balance}}', [
                'user_id' => $dto->getUserId(),
                'balance' => $dto->getBalance()
            ])->execute();
        });
    }

    /**
     * @throws Exception|\Throwable
     */
    public function save(int $userId, float $balance): void
    {
        \Yii::$app->db->transaction(function () use ($userId, $balance) {
            \Yii::$app->db->createCommand()
                ->update('{{user_balance}}',
                    [
                        'balance' => $balance
                    ],
                    ['user_id' => $userId]
                )->execute();
        });
    }
}
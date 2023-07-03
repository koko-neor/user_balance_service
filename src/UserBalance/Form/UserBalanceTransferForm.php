<?php

namespace app\src\UserBalance\Form;

use app\models\User;
use yii\base\Model;

/**
 * Class UserBalanceTransferForm
 */
class UserBalanceTransferForm extends Model
{
    public int $fromUserId;
    public int $toUserId;
    public float $balance;

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'fromUserId'    => 'Отправляемый пользователь',
            'toUserId'      => 'Принимаемый пользователь',
            'balance'       => 'Баланс',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['fromUserId', 'toUserId', 'balance'], 'required'],
            ['fromUserId', 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
            ['toUserId', 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
            ['balance', 'number'],
        ];
    }
}
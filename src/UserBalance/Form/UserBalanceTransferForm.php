<?php

namespace app\src\UserBalance\Form;

use app\models\User;
use yii\base\Model;

/**
 * Class UserBalanceTransferForm
 */
class UserBalanceTransferForm extends Model
{
    public string $fromUserId;
    public string $toUserId;
    public string $balance;

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
            [
                'fromUserId', 'exist',
                'targetClass' => User::class,
                'targetAttribute' => 'id',
                'message' => 'Пользователь не найден.'
            ],
            [
                'toUserId', 'exist',
                'targetClass' => User::class, 'targetAttribute' => 'id',
                'message' => 'Пользователь не найден.'
            ],
            [
                'balance', 'number',
                'numberPattern' => '/^\d{1,15}(\.\d{1,4})?$/',
                'message' => 'Баланс должен быть числом с максимум 15 цифрами и 4 знаками после запятой.'
            ],
        ];
    }
}
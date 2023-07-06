<?php

namespace app\src\UserBalance\Form;

use app\models\User;
use yii\base\Model;

/**
 * Class UserBalanceForm
 */
class UserBalanceForm extends Model
{
    public string $userId;
    public string $balance;
    public string $created_at;
    public string $updated_at;

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'userId'       => 'Пользователь',
            'balance'       => 'Баланс',
            'created_at'    => 'Дата создания',
            'updated_at'    => 'Дата обновления',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['userId', 'balance'], 'required'],
            [
                'userId', 'exist',
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
<?php

namespace app\src\UserBalance\Form;

use app\models\User;
use yii\base\Model;

/**
 * Class UserBalanceGetForm
 */
class UserBalanceGetForm extends Model
{
    public string $userId;
    public ?string $currency = null;

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'userId'        => 'Пользователь',
            'currency'      => 'Валюта',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['userId'], 'required'],
            [
                'userId', 'exist',
                'targetClass' => User::class, 'targetAttribute' => 'id',
                'message' => 'Пользователь не найден.'
            ],
            [
                'currency', 'string',
                'message' => 'Валюта, в которую нужно конвертировать должен быть строкой.'
            ],
        ];
    }
}
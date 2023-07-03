<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * User model
 *
 * @property integer $id
 * @property string  $username
 * @property string  $email
 * @property integer $created_at
 * @property integer $updated_at
 *  */
class User extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }
}

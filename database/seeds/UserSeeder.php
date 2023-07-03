<?php

namespace app\database\seeds;

use yii\db\Migration;
use app\models\User;

class UserSeeder extends Migration
{
    public function run()
    {
        $users = [
            ['username' => 'Арман', 'email' => 'arman@example.com'],
            ['username' => 'Нурлан', 'email' => 'nurlan@example.com'],
            ['username' => 'Жарас', 'email' => 'zharas@example.com'],
            // и так далее...
        ];

        $this->batchInsert('{{%user}}', ['username', 'email'], $users);
    }
}

<?php

use yii\db\Migration;
use app\database\seeds\UserSeeder;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m230630_103446_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id'                => $this->primaryKey(),
            'username'          => $this->string()->null(),
            'email'             => $this->string()->notNull()->unique(),
            'created_at'        => $this->timestamp()->defaultValue(null),
            'updated_at'        => $this->timestamp()->defaultValue(null),
        ], $tableOptions);

        // Вызов сидера для добавления пользователей
        $seeder = new UserSeeder();
        $seeder->run();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}

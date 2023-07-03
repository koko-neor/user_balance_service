<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_balance}}`.
 */
class m230630_104345_create_user_balance_table extends Migration
{
    public string $table               = 'user_balance';
    public string $userTable           = 'user';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable("{{{$this->table}}}", [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'balance' => $this->decimal(19, 4)->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->defaultValue(null),
            'updated_at' => $this->dateTime()->defaultValue(null),
        ], $tableOptions);

        // Добавление индекса для атрибута user_id
        $this->createIndex('idx-user_balance-user_id', 'user_balance', 'user_id');

        $onUpdateConstraint = 'RESTRICT';
        if ($this->db->driverName === 'sqlsrv') {
            $onUpdateConstraint = 'NO ACTION';
        }
        $this->addForeignKey("fk_{$this->table}_{$this->userTable}", "{{{$this->table}}}", 'user_id', "{{{$this->userTable}}}", 'id', 'CASCADE', $onUpdateConstraint);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаление индекса для атрибута user_id
        $this->dropIndex('idx-user_balance-user_id', 'user_balance');

        $this->dropForeignKey("fk_{$this->userTable}_{$this->table}", "{{{$this->userTable}}}");
        $this->dropTable("{{{$this->table}}}");
    }
}

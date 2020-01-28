<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bank_account}}`.
 */
class m200125_143823_create_bank_accounts_table extends Migration
{
    private $tableName = '{{%bank_accounts}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_info' => $this->string(),
            'money' => $this->decimal(13,4)->defaultValue(0),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ], $tableOptions);

        $this->insert($this->tableName, [
            'id' => 1,
            'user_info' => 'user_test',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bank_account}}');
    }
}

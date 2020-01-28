<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m200125_143911_create_users_table extends Migration
{
    private $tableName = '{{%users}}';

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
            'bank_account_id' => $this->integer()->notNull(),
            'username' => $this->string(30)->notNull()->unique(),
            'email' => $this->string()->defaultValue(null),
            'password' => $this->string()->notNull(),
            'auth_key' => $this->string()->defaultValue(null),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'loyalty_points' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ], $tableOptions);

        // creates index for column `bank_account_id`
        $this->createIndex(
            'idx-users-bank_account_id',
            'users',
            'bank_account_id'
        );

        // add foreign key for table `bank_accounts`
        $this->addForeignKey(
            'fk-users-bank_account_id',
            'users',
            'bank_account_id',
            'bank_accounts',
            'id',
            'CASCADE'
        );

        $this->insert($this->tableName, [
            'username' => 'user_test',
            'bank_account_id' => 1,
            'email' => 'email.test@gmail.com',
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password' => Yii::$app->getSecurity()->generatePasswordHash('password_test'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }


}

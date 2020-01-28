<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%prize_orders}}`.
 */
class m200125_184714_create_prize_orders_table extends Migration
{
    private $tableName = '{{%prize_orders}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%prize_orders}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'prize_id' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue(1),
            'count' => $this->integer()->notNull(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ], $tableOptions);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-prize_orders-user_id',
            'prize_orders',
            'user_id'
        );

        // creates index for column `prize_id`
        $this->createIndex(
            'idx-prize_orders-prize_id',
            'prize_orders',
            'prize_id'
        );

        // add foreign key for table `prize_names`
        $this->addForeignKey(
            'fk-prize_orders-prize_id',
            'prize_orders',
            'prize_id',
            'prizes',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%prize_orders}}');
    }
}

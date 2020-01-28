<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%prize_names}}`.
 */
class m200125_162004_create_prize_names_table extends Migration
{
    private $tableName = '{{%prize_names}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%prize_names}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),

        ], $tableOptions);

        $this->insert($this->tableName, ['id' => 1, 'name' => 'Loyalty points',]);
        $this->insert($this->tableName, ['id' => 2, 'name' => 'Money',]);
        $this->insert($this->tableName, ['id' => 3, 'name' => 'Watch',]);
        $this->insert($this->tableName, ['id' => 4, 'name' => 'T-shirt',]);
        $this->insert($this->tableName, ['id' => 5, 'name' => 'Auto',]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%prize_names}}');
    }
}

<?php

use yii\db\Migration;
use app\models\Prize;

/**
 * Handles the creation of table `{{%prizes}}`.
 */
class m200125_162139_create_prizes_table extends Migration
{
    private $tableName = '{{%prizes}}';

   /* private $TYPE_MONEY = 'money';
    private $TYPE_LOYALTY_POINTS = 'loyalty_points';
    private $TYPE_THING = 'thing';*/

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
            'type' => $this->string()->notNull(),
            'prize_name_id' => $this->integer()->notNull(),
            'count' => $this->integer()->defaultValue(0),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),

        ], $tableOptions);

        // creates index for column `prize_name_id`
        $this->createIndex(
            'idx-prizes-prize_name_id',
            'prizes',
            'prize_name_id'
        );

        // add foreign key for table `prize_names`
        $this->addForeignKey(
            'fk-prizes-prize_name_id',
            'prizes',
            'prize_name_id',
            'prize_names',
            'id',
            'CASCADE'
        );

        $this->insert($this->tableName, [
            'type' => Prize::TYPE_LOYALTY_POINTS,
            'prize_name_id' => 1,
        ]);

        $this->insert($this->tableName, [
            'type' => Prize::TYPE_MONEY,
            'prize_name_id' => 2,
            'count' => 100000,
        ]);

        $this->insert($this->tableName, [
            'type' => Prize::TYPE_THING,
            'prize_name_id' => 3,
            'count' => 5,
        ]);

        $this->insert($this->tableName, [
            'type' => Prize::TYPE_THING,
            'prize_name_id' => 4,
            'count' => 10,
        ]);

        $this->insert($this->tableName, [
            'type' => Prize::TYPE_THING,
            'prize_name_id' => 5,
            'count' => 3,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%prizes}}');
    }
}

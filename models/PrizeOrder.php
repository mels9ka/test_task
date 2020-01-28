<?php

namespace app\models;

use app\models\query\PrizeOrderQuery;
use Yii;

/**
 * This is the model class for table "prize_orders".
 *
 * @property int $id
 * @property int $user_id
 * @property int $prize_id
 * @property int $status
 * @property int $count
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Prize $prize
 * @property User $user
 */
class PrizeOrder extends \yii\db\ActiveRecord
{
    const STATE_NEW = 1;
    const STATE_PROCESSING = 2;
    const STATE_COMPLETED = 3;
    const STATE_DECLINED = 4;

    /** @var bool */
    public $is_convert;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prize_orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'prize_id', 'count'], 'required'],
            [['user_id', 'prize_id', 'status', 'count'], 'integer'],
            [['created_at', 'updated_at', 'is_convert'], 'safe'],
            [['prize_id'], 'exist', 'skipOnError' => true, 'targetClass' => Prize::class, 'targetAttribute' => ['prize_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'prize_id' => 'Prize ID',
            'status' => 'Status',
            'count' => 'Count',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Prize]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrize()
    {
        return $this->hasOne(Prize::class, ['id' => 'prize_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function find()
    {
        return new PrizeOrderQuery(get_called_class());
    }
}

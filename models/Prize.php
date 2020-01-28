<?php

namespace app\models;

use app\models\query\PrizeQuery;
use Yii;

/**
 * This is the model class for table "prizes".
 *
 * @property int $id
 * @property string $type
 * @property int $prize_name_id
 * @property int|null $count
 * @property string $created_at
 * @property string $updated_at
 *
 * @property PrizeName $prizeName
 */
class Prize extends \yii\db\ActiveRecord
{
    const TYPE_MONEY = 'money';
    const TYPE_LOYALTY_POINTS = 'loyalty_points';
    const TYPE_THING = 'thing';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prizes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'prize_name_id'], 'required'],
            [['prize_name_id', 'count'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['type'], 'string', 'max' => 255],
            [['prize_name_id'], 'exist', 'skipOnError' => true, 'targetClass' => PrizeName::class, 'targetAttribute' => ['prize_name_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'prize_name_id' => 'Prize Name ID',
            'count' => 'Count',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[PrizeName]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrizeName()
    {
        return $this->hasOne(PrizeName::class, ['id' => 'prize_name_id']);
    }

    /**
     *
     * @return Prize[];
     */
    public static function getAvaliablePrizes() : iterable
    {
        $prizes = self::find()->where(['>', 'count', 0])
            ->orWhere(['like', 'type', self::TYPE_LOYALTY_POINTS])->all();

        return $prizes;
    }

    public static function find()
    {
        return new PrizeQuery(get_called_class());
    }
}

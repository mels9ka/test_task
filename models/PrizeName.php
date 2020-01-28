<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prize_names".
 *
 * @property int $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Prizes[] $prizes
 */
class PrizeName extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prize_names';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Prizes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrizes()
    {
        return $this->hasMany(Prizes::className(), ['prize_name_id' => 'id']);
    }
}

<?php

namespace app\modules\api\v1\models;

use Yii;

/**
 * This is the model class for table "bank_accounts".
 *
 * @property int $id
 * @property string|null $user_info
 * @property float|null $money
 * @property string $created_at
 * @property string $updated_at
 *
 */
class BankAccount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bank_accounts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['money'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_info'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_info' => 'User Info',
            'money' => 'Money',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

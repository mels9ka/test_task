<?php

namespace app\models;

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
 * @property User[] $users
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

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['bank_account_id' => 'id']);
    }
}

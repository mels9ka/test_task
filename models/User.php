<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $username
 * @property int $bank_account_id
 * @property string $email
 * @property string $password
 * @property string $auth_key
 * @property int $status
 * @property int $loyalty_points
 * @property string $created_at
 * @property string $updated_at
 *
 *  * @property BankAccount $bankAccount
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['bank_account_id', 'status', 'loyalty_points'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['username'], 'string', 'max' => 30],
            [['email', 'password', 'auth_key'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['bank_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => BankAccount::class, 'targetAttribute' => ['bank_account_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'auth_key' => 'Auth Key',
            'status' => 'Status',
            'loyalty_points' => 'Loyalty points',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @param $token string
     * @param  null $type
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    /**
     * @param $password string
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    public static function findByUsername($username)
    {
        return static::find()->where(['username' => $username])->one();
    }

    /**
     * Gets query for [[BankAccount]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBankAccount()
    {
        return $this->hasOne(BankAccount::class, ['id' => 'bank_account_id']);
    }
}

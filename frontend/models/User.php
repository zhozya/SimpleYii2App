<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $verification_token
 *
 * @property Client[] $clients
 * @property Client[] $clients0
 * @property Client[] $clients1
 * @property Club[] $clubs
 * @property Club[] $clubs0
 * @property Club[] $clubs1
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verification_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
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
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'verification_token' => 'Verification Token',
        ];
    }

    /**
     * Gets query for [[Clients]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClients()
    {
        return $this->hasMany(Client::class, ['creator_id' => 'id']);
    }

    /**
     * Gets query for [[Clients0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClients0()
    {
        return $this->hasMany(Client::class, ['deleter_id' => 'id']);
    }

    /**
     * Gets query for [[Clients1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClients1()
    {
        return $this->hasMany(Client::class, ['updater_id' => 'id']);
    }

    /**
     * Gets query for [[Clubs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClubs()
    {
        return $this->hasMany(Club::class, ['creator_id' => 'id']);
    }

    /**
     * Gets query for [[Clubs0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClubs0()
    {
        return $this->hasMany(Club::class, ['deleter_id' => 'id']);
    }

    /**
     * Gets query for [[Clubs1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClubs1()
    {
        return $this->hasMany(Club::class, ['updater_id' => 'id']);
    }
}

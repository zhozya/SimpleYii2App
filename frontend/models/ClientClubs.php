<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client_clubs".
 *
 * @property int $id
 * @property int $client_id
 * @property int $club_id
 *
 * @property Client $client
 * @property Club $club
 */
class ClientClubs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client_clubs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'club_id'], 'required'],
            [['client_id', 'club_id'], 'integer'],
            [['client_id', 'club_id'], 'unique', 'targetAttribute' => ['client_id', 'club_id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::class, 'targetAttribute' => ['client_id' => 'id']],
            [['club_id'], 'exist', 'skipOnError' => true, 'targetClass' => Club::class, 'targetAttribute' => ['club_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client ID',
            'club_id' => 'Club ID',
        ];
    }

    /**
     * Gets query for [[Client]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }

    /**
     * Gets query for [[Club]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClub()
    {
        return $this->hasOne(Club::class, ['id' => 'club_id']);
    }
}

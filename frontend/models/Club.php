<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "club".
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property int $creator_id
 * @property int $created_at
 * @property int $updater_id
 * @property int $updated_at
 * @property int|null $deleter_id
 * @property int|null $deleted_at
 *
 * @property ClientClubs[] $clientClubs
 * @property Client[] $clients
 * @property User $creator
 * @property User $deleter
 * @property User $updater
 */
class Club extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'club';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'address'], 'trim'],
            [['name', 'address'], 'required'],
            [['creator_id', 'created_at', 'updater_id', 'updated_at', 'deleter_id', 'deleted_at'], 'integer'],
            [['name'], 'string', 'min' => 4, 'max' => 255],
            [['address'], 'string'],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id']],
            [['deleter_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['deleter_id' => 'id']],
            [['updater_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updater_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'address' => 'Адрес',
            'creator_id' => 'Создал',
            'created_at' => 'Когда создано',
            'updater_id' => 'Обновил',
            'updated_at' => 'Когда обновлено',
            'deleter_id' => 'Удалил',
            'deleted_at' => 'Когда удалено',
        ];
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'creator_id',
                'updatedByAttribute' => 'updater_id',
            ],
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => time(),
            ],
        ];
    }

    public static function findAllActive(): array
    {
        return self::findAll(['deleted_at' => null]);
    }

    /**
     * Gets query for [[ClientClubs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClientClubs()
    {
        return $this->hasMany(ClientClubs::class, ['club_id' => 'id']);
    }

    /**
     * Gets query for [[Clients]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClients()
    {
        return $this->hasMany(Client::class, ['id' => 'client_id'])->viaTable('client_clubs', ['club_id' => 'id']);
    }

    /**
     * Gets query for [[Creator]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::class, ['id' => 'creator_id']);
    }

    /**
     * Gets query for [[Deleter]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeleter()
    {
        return $this->hasOne(User::class, ['id' => 'deleter_id']);
    }

    /**
     * Gets query for [[Updater]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdater()
    {
        return $this->hasOne(User::class, ['id' => 'updater_id']);
    }
}

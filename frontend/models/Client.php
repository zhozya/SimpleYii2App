<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "client".
 *
 * @property int $id
 * @property string $name
 * @property int $is_male
 * @property string $date_of_birth
 * @property int $creator_id
 * @property int $created_at
 * @property int $updater_id
 * @property int $updated_at
 * @property int|null $deleter_id
 * @property int|null $deleted_at
 *
 * @property ClientClubs[] $clientClubs
 * @property Club[] $clubs
 * @property User $creator
 * @property User $deleter
 * @property User $updater
 */
class Client extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'client';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'is_male', 'date_of_birth'], 'required'],
            [['is_male', 'creator_id', 'created_at', 'updater_id', 'updated_at', 'deleter_id', 'deleted_at'], 'integer'],
            [['date_of_birth'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id']],
            [['deleter_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['deleter_id' => 'id']],
            [['updater_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updater_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'ФИО',
            'is_male' => 'Пол',
            'date_of_birth' => 'Дата рождения',
            'creator_id' => 'Создал',
            'created_at' => 'Создано',
            'updater_id' => 'Обновил',
            'updated_at' => 'Обновлено',
            'deleter_id' => 'Удалил',
            'deleted_at' => 'Удалено',
        ];
    }

    public function saveWithClubs(?array $clubIDs): bool
    {
        $tr = Yii::$app->db->beginTransaction();

        try {
            if (!$this->save()) {
                throw new \Exception("Не удалось сохранить клиента");
            }

            if ($this->primaryKey) {
                ClientClubs::deleteAll(['client_id' => $this->primaryKey]);
            }

            if ($clubIDs) {
                foreach ($clubIDs as $clubID) {
                    $clientsClubs = new ClientClubs([
                        'client_id' => $this->primaryKey,
                        'club_id' => (int)$clubID,
                    ]);
                    if (!$clientsClubs->save()) {
                        throw new \Exception("Не удалось сохранить клуб '$clubID' клиенту");
                    }
                }
            }
        } catch (\Exception $e) {
            $tr->rollBack();
            throw $e;
        }

        $tr->commit();
        return true;
    }

    public function getSexAlias(): string
    {
        return $this->is_male ? 'Муж.' : 'Жен.';
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

    /**
     * Gets query for [[ClientClubs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClientClubs()
    {
        return $this->hasMany(ClientClubs::class, ['client_id' => 'id']);
    }

    /**
     * Gets query for [[Clubs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClubs()
    {
        return $this->hasMany(Club::class, ['id' => 'club_id'])->viaTable('client_clubs', ['client_id' => 'id']);
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

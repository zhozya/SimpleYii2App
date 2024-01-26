<?php

namespace app\models;

use yii\base\Model;

class ClientForm extends Model
{
    public ?int $id = null;
    public ?string $name = null;
    public ?int $isMale = null;
    public ?string $dateOfBirth = null;
    public ?array $clubs = null;

    public function rules(): array
    {
        return [
            [['name', 'isMale', 'dateOfBirth'], 'required'],
            [['id', 'isMale', 'creator_id', 'created_at', 'updater_id', 'updated_at', 'deleter_id', 'deleted_at'], 'integer'],
            [['dateOfBirth'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['clubs'], 'each', 'rule' => ['integer']]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'ФИО',
            'isMale' => 'Пол',
            'dateOfBirth' => 'Дата рождения',
            'clubs' => 'Клубы',
        ];
    }

    public function load($data, $formName = null): bool
    {
        if (isset($data['ClientForm']['clubs']) && !$data['ClientForm']['clubs']) {
            $data['ClientForm']['clubs'] = null;
        }
        return parent::load($data, $formName);
    }

    public function loadClientData(Client $client): void
    {
        $this->id = $client->id;
        $this->name = $client->name;
        $this->isMale = $client->is_male;
        $this->dateOfBirth = $client->date_of_birth;
        $this->clubs = $client->clubs;
    }

    public function save(): bool
    {
        $client = $this->id ? Client::findOne(['id' => $this->id]) : new Client();
        if (!$client) {
            throw new \Exception('Клиент не найден');
        }

        $client->name = $this->name;
        $client->date_of_birth = $this->dateOfBirth;
        $client->is_male = (int)$this->isMale;

        if ($client->saveWithClubs($this->clubs)) {
            $this->id = $client->id;
            return true;
        }
        return false;
    }
}
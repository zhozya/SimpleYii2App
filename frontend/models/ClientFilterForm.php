<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class ClientFilterForm extends Model
{
    public ?string $name = null;
    public ?string $sex = null;
    public ?string $dateRange = null;

    public function rules(): array
    {
        return [
            [['name', 'dateRange', 'sex'], 'string', 'max' => 255],
            [['dateRange'], 'match', 'pattern' => '/^\d{4}-\d{2}-\d{2} - \d{4}-\d{2}-\d{2}$/i'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'ФИО',
            'sex' => 'Пол',
            'dateRange' => 'Дата рождения'
        ];
    }

    public function search(array $requestData): ActiveDataProvider
    {
        $this->load($requestData);

        $query = Client::find()
            ->where(['deleted_at' => null])
            ->with('clubs');

        if ($this->name) {
            $query->andWhere(['like', 'name', $this->name]);
        }

        if ($this->sex) {
            $query->andWhere(['is_male' => $this->sex === 'male' ? 1 : 0]);
        }

        if ($this->validateDateRange()) {
            $query->andWhere(['between', 'date_of_birth', $this->getDateStart(), $this->getDateEnd()]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
    }

    public function getDateStart(): ?string
    {
        return $this->getDateByIndex(0);
    }

    public function getDateEnd(): ?string
    {
        return $this->getDateByIndex(1);
    }

    public function validateDateRange(): bool
    {
        return $this->dateRange && $this->validate('dateRange');
    }

    private function getDateByIndex(int $index): ?string
    {
        if (!$this->validateDateRange()) {
            return null;
        }
        return explode(' - ', $this->dateRange)[$index];
    }
}
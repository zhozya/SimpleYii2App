<?php

namespace app\models;

use yii\base\Model;

class ClientFilterForm extends Model
{
    public ?string $name = null;
    public mixed $isMale = null;
    public ?string $dateRange = null;

    public function rules(): array
    {
        return [
            [['name', 'dateRange'], 'string', 'max' => 255],
            [['isMale'], 'integer'],
            [['dateRange'], 'match', 'pattern' => '/^\d{4}-\d{2}-\d{2} - \d{4}-\d{2}-\d{2}$/i'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'ФИО',
            'isMale' => 'Пол',
            'dateRange' => 'Дата рождения'
        ];
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
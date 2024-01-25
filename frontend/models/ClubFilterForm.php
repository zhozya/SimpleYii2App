<?php

namespace app\models;

use yii\base\Model;

class ClubFilterForm extends Model
{
    public ?string $name = null;
    public bool $isShowDeleted = false;

    public function rules(): array
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['isShowDeleted'], 'boolean']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
            'isShowDeleted' => 'Архив',
        ];
    }
}
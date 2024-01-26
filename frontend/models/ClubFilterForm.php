<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

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

    public function search(array $requestData): ActiveDataProvider
    {
        $this->load($requestData);

        $query = Club::find();

        if ($this->name) {
            $query->andWhere(['like', 'name', $this->name]);
        }
        if (!$this->isShowDeleted) {
            $query->andWhere(['deleted_at' => null]);
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
}
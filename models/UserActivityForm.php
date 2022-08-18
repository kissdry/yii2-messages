<?php

namespace app\models;

use yii\base\Model;

class UserActivityForm extends Model
{
    public string $period_start = '';
    public string $period_end = '';
    public int $limit = 0;
    public string $dir = '';

    const DIR_ASC = 'asc';
    const DIR_DESC = 'desc';

    public function rules(): array
    {
        return [
            [['period_start', 'period_end', 'limit', 'dir'], 'required'],
            [['period_start', 'period_end'], 'match', 'pattern' => '/^\d{4}-\d{2}-\d{2}$/i'],
            ['limit', 'integer', 'min' => 1],
            ['dir', 'in', 'range' => [self::DIR_ASC, self::DIR_DESC]],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'period_start' => 'Period Start',
            'period_end' => 'Period End',
            'limit' => 'Users Limit',
            'dir' => 'Sort Direction',
        ];
    }

    public function getUserActivity(): array
    {
        $query = User::find()
            ->select(['`user`.name', 'COUNT(`message`.id) AS `msgCount`'])
            ->leftJoin('message','message.from_user_id = user.id OR message.to_user_id = user.id')
            ->where(['between', 'created_at', strtotime($this->period_start), strtotime($this->period_end)])
            ->groupBy('`user`.id')
            ->orderBy(['msgCount' => ($this->dir === self::DIR_ASC) ? SORT_ASC : SORT_DESC])
            ->limit($this->limit);

        return $query->asArray()->all();
    }
}

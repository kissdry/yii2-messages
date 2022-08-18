<?php

namespace app\models;

use yii\base\Model;
use yii\db\Expression;

class TotalMessagesForm extends Model
{
    public string $period_start = '';
    public string $period_end = '';
    public string $period_group_unit = '';

    const GROUP_DAY = 'day';
    const GROUP_MONTH = 'month';
    const GROUP_YEAR = 'year';

    public function rules(): array
    {
        return [
            [['period_start', 'period_end', 'period_group_unit'], 'required'],
            [['period_start', 'period_end'], 'match', 'pattern' => '/^\d{4}-\d{2}-\d{2}$/i'],
            ['period_group_unit', 'in', 'range' => [self::GROUP_DAY, self::GROUP_MONTH, self::GROUP_YEAR]],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'period_start' => 'Period Start',
            'period_end' => 'Period End',
            'period_group_unit' => 'Period Group Unit',
        ];
    }

    public function getTotalMessages()
    {
        switch ($this->period_group_unit) {
            case self::GROUP_DAY:
                $grouper = 'DATE_FORMAT(FROM_UNIXTIME(`created_at`), "%Y-%m-%d")';
                break;
            case self::GROUP_MONTH:
                $grouper = 'DATE_FORMAT(FROM_UNIXTIME(`created_at`), "%Y-%m")';
                break;
            case self::GROUP_YEAR:
                $grouper = 'DATE_FORMAT(FROM_UNIXTIME(`created_at`), "%Y")';
                break;
        }

        $query = Message::find()
            ->select(['COUNT(`message`.id) AS `message_number`', new Expression("$grouper as `period`")])
            ->where(['between', 'created_at', strtotime($this->period_start), strtotime($this->period_end)])
            ->groupBy(new Expression($grouper))
            ->orderBy([$grouper => SORT_ASC]);

        $records = $query->asArray()->all();
        // return $records;

        foreach ($records as $k => $record) {
            $period = $record['period'];
            unset($records[$k]['period']);
            switch ($this->period_group_unit) {
                case self::GROUP_DAY:
                    $records[$k]['period_start'] = $period . ' 00:00:00';
                    $records[$k]['period_end'] = $period . ' 23:59:59';
                    break;
                case self::GROUP_MONTH:
                    list($year, $month) = explode('-', $period);
                    $daysCount = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $month = str_pad($month, 2, '0', STR_PAD_LEFT);
                    $records[$k]['period_start'] = "$year-{$month}-01 00:00:00";
                    $records[$k]['period_end'] = "$year-{$month}-{$daysCount} 23:59:59";
                    break;
                case self::GROUP_YEAR:
                    $records[$k]['period_start'] = $period . '-01-01 00:00:00';
                    $records[$k]['period_end'] = $period . '-12-31 23:59:59';
                    break;
            }
        }
        return $records;
    }
}

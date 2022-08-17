<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Message model.
 *
 * @property int $id
 * @property string $content
 * @property int $created_at
 * @property int $from_user_id
 * @property int $to_user_id
 */
class Message extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%message}}';
    }

    /**
     * @return ActiveQuery
     */
    public function getSender(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'from_user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getReceiver(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'to_user_id']);
    }
}

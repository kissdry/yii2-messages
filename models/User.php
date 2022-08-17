<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * User model.
 *
 * @property int $id
 * @property string $name
 */
class User extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * @return ActiveQuery
     */
    public function getSentMessages(): ActiveQuery
    {
        return $this->hasMany(Message::class, ['from_user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getReceivedMessages(): ActiveQuery
    {
        return $this->hasMany(Message::class, ['to_user_id' => 'id']);
    }
}

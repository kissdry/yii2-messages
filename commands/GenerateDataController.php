<?php

namespace app\commands;

use app\models\Message;
use app\models\User;
use Yii;
use yii\base\Exception;
use yii\console\Controller;
use \Faker;
use yii\db\QueryBuilder;

class GenerateDataController extends Controller
{
    public int $user = 10;
    public int $message = 100;

    // text will be used like this: strtotime('-text')
    const MESSAGES_MAX_AGE = '5 years';

    public function options($actionID): array
    {
        return array_merge(parent::options($actionID), ['user', 'message']);
    }

    /**
     * Truncates and fills `user` and `message` tables with random data.
     * Use --user and --message params to set users and messages count respectively.
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function actionTest()
    {
        if ($this->user < 2) {
            throw new Exception('Users count must be >= 2');
        }
        if ($this->message < 1) {
            throw new Exception('Messages count must be >= 1');
        }

        Yii::$app->db->createCommand()->truncateTable('user')->execute();
        Yii::$app->db->createCommand()->truncateTable('message')->execute();

        $users = $this->generateRandomUsers($this->user);
        $messages = $this->generateRandomMessages($this->message, $users);

        $queryBuilder = new QueryBuilder(Yii::$app->db);
        $query = $queryBuilder->batchInsert(User::tableName(), array_keys($users[1]), $users);
        Yii::$app->db->createCommand($query)->execute();
        $query = $queryBuilder->batchInsert(Message::tableName(), array_keys($messages[1]), $messages);
        Yii::$app->db->createCommand($query)->execute();

        echo 'Data generated.' . PHP_EOL;
    }

    protected function generateRandomUsers(int $count): array
    {
        $faker = Faker\Factory::create();
        $users = [];
        $names = [];

        for ($id = 1; $id <= $count; $id++) {
            do {
                $name = $faker->name;
            } while (isset($names[$name]));
            $users[$id] = [
                'id' => $id,
                'name' => $name,
            ];
            $names[$name] = 1;
        }

        return $users;
    }

    protected function generateRandomMessages(int $count, array $users): array
    {
        $faker = Faker\Factory::create();
        $usersIds = array_keys($users);
        $usersCount = count($usersIds);
        $firstMessageTime = strtotime('-' . self::MESSAGES_MAX_AGE);
        $lastMessageTime = time();
        $messages = [];

        for ($id = 1; $id <= $count; $id++) {
            $fromUserId = $usersIds[rand(0, $usersCount-1)];
            do {
                $toUserId = $usersIds[rand(0, $usersCount-1)];
            } while ($fromUserId === $toUserId);
            $messages[$id] = [
                'id' => $id,
                'content' => $faker->text(),
                'created_at' => rand($firstMessageTime, $lastMessageTime),
                'from_user_id' => $fromUserId,
                'to_user_id' => $toUserId,
            ];
        }

        return $messages;
    }
}

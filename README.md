INSTALL AND SETUP
-------------
- Clone the repository:
```
git clone kissdry/yii2-messages
```

- Install dependencies:
```
composer install
```

- copy `config/db-example.php` to `config/db.php`, edit `config/db.php` with real data
- run migrations:
```
./yii migrate
```
- generate random user and message data using command:
```
./yii generate-data/test --user=15 --message=225
```

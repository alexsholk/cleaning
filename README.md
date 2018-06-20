## SETUP

### Install dependencies
```
composer install 
yarn install 
gulp
```

### Create DB and load data

Setup DB connection in *.env* and run:

```
php bin/console doctrine:database:create
php bin/console doctrine:schema:create
php bin/console app:parse:streets
php bin/console doctrine:fixtures:load
```
### Start server
```
php bin/console server:start
```

### Admin account

Go to */admin* and type:

- Login: admin
- Password: admin
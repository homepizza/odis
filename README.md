# ODIS
Open development AS IS. (Task manager)

`composer install`

`yarn install` - если используете его

`sudo chmod -R 777 public`

`sudo chmod -R 777 var`

в `.env` - `prod` + `dsn to database`

`php bin/console d:m:m`

`php bin/console d:f:l`

`yarn encore production` - можно `prod`

# Регистрация вручную

Для ручной регистрации, есть таска `php bin/console user:new`  ключи `--developer` или `--author`

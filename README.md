Выполнено на Symfony 3

Прописываем параметры соединения к БД в  app/config/parameters.yml

Для создания БД команда
php bin/console doctrine:database:create

Для создания таблиц
php bin/console doctrine:schema:update --force

Для выгрузки словаря нужно выполнить миграцию
php bin/console doctrine:migrations:migrate
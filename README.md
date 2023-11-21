## Запуск приложения
1. Указать в .env доступы к базе, пример c PostgreSQL:
```yaml
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=guestbook
   DB_USERNAME=postgres
   DB_PASSWORD=password
```
2. Выполнить команды 
```shell
composer install
php artisan key:generate
php artisan migrate
 ```
3. Запустить сервер с помощью ``php artisan serve`` либо через OpenServer(версия php ^8.1)
4. Сервер должен быть доступен по URL: http://localhost:8000
5. Для того чтобы очистка капчи выполнялась автоматически добавляем в крон (/etc/crontab по умолчанию) строку
```yaml
* * * * * php var/www/guestbook/artisan schedule:run >> /dev/null 2>&1
```
Где var/www/guestbook - путь к проекту
## What use instead of OFFSET?

Cursor pagination,в отличие от оффсета скипает только те строки, которые находятся перед курсором, а не фиксированное количество и не проверяет каждое значение <br>
Пример: <br>
``
    SELECT * FROM posts
    WHERE id (уникальный ключ) > last_cursor
    ORDER BY id
    LIMIT 10;
``

## Запуск приложения
1. Указать в .env доступы к базе.
2. Накатить миграции с помощью ``php artisan migrate``
3. Запустить сервер с помощью ``php artisan serve`` либо через OpenServer(версия php ^8.1)

## What use instead of OFFSET?

Cursor pagination,в отличие от оффсета скипает только те строки, которые находятся перед курсором, а не фиксированное количество и не проверяет каждое значение <br>
Пример: <br>
``
    SELECT * FROM posts
    WHERE id (уникальный ключ) > last_cursor
    ORDER BY id
    LIMIT 10;
``

#  REST API для управления списком задач

## Запуск приложения

После клонирования репозитория, в папке проекта:

    composer install

Далее создать .env файла на основе .env.example:

    cp .env.example .env

Сгенерировать ключ приложения:

    php artisan key:generate

Запустить миграцию базы данных:

    php artisan migrate

### Функционал приложения:

- Создание задачи - POST /api/tasks
- Получение списка задач - GET /api/tasks
- Получение конкретной задачи - GET /api/tasks{id}
- Обновление задачи - PATCH /api/tasks{id}
- Удаление задачи - DELETE /api/tasks{id}  
- Swagger документация - /api/documentation#/
  
### Тестирование

- Заполнение БД тестовыми данными:

    php artisan DB:seed

- Запуск функциональных тестов:

    php artisan test

# REST API для управления списком задач

## Запуск приложения

Клонировать репозиторий и в папке проекта:

    cp .env.example .env

Заполнить основные данные в .env для БД:

    - APP_ENV
    - APP_DEBUG
    - DB_DATABASE
    - DB_USERNAME
    - DB_PASSWORD

Далее работа с Docker:

    docker compose up -d
    docker exec -it laravel-app bash
    composer install

Сгенерировать ключ приложения:

    php artisan key:generate

Запустить миграцию базы данных:

    php artisan migrate

### Функционал приложения:

- Создание задачи - POST http://localhost:8080/api/tasks
- Получение списка задач - GET http://localhost:8080/api/tasks
- Получение конкретной задачи - GET http://localhost:8080/api/tasks{id}
- Обновление задачи - PATCH http://localhost:8080/api/tasks{id}
- Удаление задачи - DELETE http://localhost:8080/api/tasks{id}
- Swagger документация - http://localhost:8080/api/documentation#/

### Тестирование

- Заполнение БД тестовыми данными:

  php artisan DB:seed

- Запуск функциональных тестов:

  php artisan test

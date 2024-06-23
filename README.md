# Inspirelink

Inspirelink - это платформа, предназначенная для объединения людей с общими интересами и целями. С помощью Inspirelink вы можете находить партнеров для совместных проектов, стартапов или просто для общения по интересам. Платформа предоставляет возможности для создания команд, общения в чатах, публикации постов и многого другого.

## Стек технологий

### Backend

-   **Основной стек**: PHP, Laravel
-   **Развертывание**: Docker, Docker-Compose, Nginx
-   **СУБД**: MySQL
-   **Кэширование**: Redis
-   **Очереди**: RabbitMQ
-   **Полнотекстовый поиск**: Elasticsearch
-   **Логирование**: Monolog, Files
-   **Метрики**: Prometheus
-   **Сообщения**: Firebase

### Front-end

-   Coming soon...

## Установка и запуск (Docker)

Для развертывания проекта выполните следующие шаги:

### Настройка окружения

1. **Создание файла .env**:
    - Скопируйте содержимое `./backend/.env.example` в новый файл `./backend/.env`. Все необходимые стандартные переменные окружения уже настроены.

### Сборка и запуск проекта

2. **Сборка контейнеров**:
    - Соберите проект командой: `docker-compose up --build -d`.

### Backend

3. **Создание таблиц базы данных**:
    - Выполните: `docker-compose exec backend php artisan migrate`.
4. **(Опционально) Заполнение базы данных фейковыми данными**:
    - Выполните: `docker-compose exec backend php artisan db:seed`.
    - Пароль для всех пользователей, созданных сидером, будет 'password'.

5. **Доступ к API**:
    - Используйте `localhost/api` для доступа к API.

### Frontend

- Coming soon.

### Доступ к приложению

6. **Доступ к frontend**:
    - Coming soon.
  
### Тестирование

9. **Запуск автотестов**:
    - Автотесты можно запустить командой: `docker-compose exec backend php artisan test`.

## (Для разработчиков) Дополнительные шаги конфигурации

Перед началом работы над проектом необходимо выполнить следующие дополнительные шаги конфигурации:

1. **Настройка Firebase для аутентификации**:
   - Скопируйте файл `firebase-auth.json` в папку `backend`.

2. **Настройка Firebase Storage**:
   - Установите индивидуальный `FIREBASE_STORAGE_BUCKET` в файле `.env`.

# TODO

## Completed Features

1. **Users and Authorization**
   - User registration and login functionality.

2. **Teams and Participants**
   - Create and manage teams.
   - Team links, vacancies (for finding specific team members rather than formal hiring), and applications for these vacancies.

3. **Posts and Comments**
   - Users and teams can publish posts.
   - Comments on posts.

4. **Projects**
   - Projects created by users or teams.
   - Participants and project links.

5. **Tags**
   - Tags for user profiles, teams, and posts (e.g., WEB, 3D).

6. **Likes**
   - Like functionality for posts, comments, and projects.

7. **Subscriptions**
   - Subscribe to users and teams.

8. **Basic Notifications**
   - Basic notification system for user activities.

9. **Chatting**
   - Team chats, group chats, and private dialogues.
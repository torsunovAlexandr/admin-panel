# Admin Panel

Бэкенд для админ панели с разделами "Платежи" и "Пользователи". Реализована на PHP (Laravel) и MySQL.

## Установка

1. Клонируйте репозиторий:
    ```bash
    git clone https://github.com/torsunovAlexandr/admin-panel.git
    cd admin-panel
    ```

2. Настройте файл `.env`:
    ```bash
    cp .env.example .env
    ```

3. Запустите команду:
    ```bash
    make init
    ```

## Проверка подписи

Для защиты входящих данных используется подпись. Включите middleware `VerifyApiSignature` в нужные маршруты. Добавьте секретный ключ в `.env` файл:
```dotenv
API_SECRET=your_secret_key
API_KEY=your_apy_key
```

## Postman коллекция

Коллекцию запросов для взаимодействия с апи можно скачать [тут](https://drive.google.com/file/d/1C5mf0oLE0rbUdvcFAMcxqpGY5KuhGVIe/view?usp=sharing)

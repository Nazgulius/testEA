# WB API Integration

## Описание
Проект для интеграции с WB API. Загружает данные по продажам, заказам, складам и доходам.

## Настройки БД
- **Хост:** localhost
- **База данных:** laravel
- **Пользователь:** root
- **Пароль:** 12345
Параметры для подключения сайт https://cp.beget.com/mysql или 
Версия СУБД:
MySQL 8.0
Сервер для подключения сайтов:
localhost
Сервер для внешних подключений:
nazgul7f.beget.tech
Имя пользователя:
Совпадает с именем БД
phpMyAdmin:
https://center.beget.com/phpMyAdmin

пароль к БД 123456eA

## Таблицы
- `sales` — продажи
- `orders` — заказы
- `stocks` — склады
- `incomes` — доходы

## Запуск
1. Установите зависимости: `composer install`
2. Настройте `.env`
3. Запустите миграции: `php artisan migrate`
4. Запустите сервер: `php artisan serve`
5. Перейдите на `/login` и введите токен Ключ: E6kUTYrYwZq2tN4QEtyzsbEBk3ie.

## Эндпоинты
- `/login` — форма авторизации
- `/dashboard

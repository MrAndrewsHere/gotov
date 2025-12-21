<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<h1 align="center"><a href="https://frankenphp.dev"><img src="frankenphp.png" alt="FrankenPHP" width="400"></a></h1>

## Благотворительный REST API для управления проектами и пожертвованиями

(http://127.0.0.1/)

Stack:
- Laravel 11 (Octane/FrankenPHP) - PHP 8.2+
- PostgreSQL
- Redis

Dashboards:
- Horizon (http://127.0.0.1/horizon/dashboard)

# Getting Started

## Настройка окружения

1. Скопируйте файл окружения:
```bash
cp .env.example .env --update=none
```
2. Настройте необходимые переменные в `.env`:
```bash
APP_NAMESPACE=value # value - префикс к сервисам docker-compose 
```
3. Инициализация проекта:


```bash
make init
```


## Особенности реализации

### 1. PostgreSQL Триггер

Автоматическая синхронизация денормализованного поля `donation_amount` в таблице `charity_projects`:

```sql
-- Функция триггера обновляет сумму при INSERT, UPDATE, DELETE пожертвований
CREATE TRIGGER trigger_donations_insert
AFTER INSERT ON donations
FOR EACH ROW
EXECUTE FUNCTION update_charity_project_donation_amount();
```

### 2. moneyphp/money для работы с валютой

### 3. HTML Санитизация - stevebauman/purify

## Commonly used tasks

```bash
make exec # контейнер laravel
```
```bash
make up # поднять контейнеры для локальной разработки
```
```bash
make stop
```
```bash
make tink
```
```bash
make check # проверка качества кода
```

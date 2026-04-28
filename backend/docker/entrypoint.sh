#!/bin/sh
set -e

echo "Waiting for MySQL at ${DB_HOST:-db}:${DB_PORT:-3306}..."

until php -r '
$host = getenv("DB_HOST") ?: "db";
$port = getenv("DB_PORT") ?: "3306";
$database = getenv("DB_DATABASE") ?: "";
$username = getenv("DB_USERNAME") ?: "";
$password = getenv("DB_PASSWORD") ?: "";

try {
    new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password, [
        PDO::ATTR_TIMEOUT => 3,
    ]);
} catch (Throwable $e) {
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    exit(1);
}
'; do
    sleep 2
done

php artisan migrate --force

exec php -S 0.0.0.0:8000 -t public

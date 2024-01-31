# Exe Post (Backend)

Exe Pos System (Backend)

## Install the dependencies
```bash
composer install
```

### Create .env file
```bash
# windows
copy .env.example .env
# linux
cp .env.example .env
```

### Edit .env file
```bash
# App URL
APP_URL=http://localhost

# database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=exe-pos
DB_USERNAME=root
DB_PASSWORD=
```

### Create table and insert dummy data
```bash
php artisan migrate:fresh
php artisan db:seed
# or
php artisan migrate:fresh --seed
```

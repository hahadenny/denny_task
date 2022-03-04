##  This is a sample Laravel CRUD Task Application built by Denny Choi


### 1. To Clone Denny Task Application:

```bash
git clone git@github.com:hahadenny/denny_task.git
```

### 2. Install vendor dependencies:

```bash
cd denny_task
composer install
```

### 3. Update database data in .env file

```bash
vi .env

DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

### 4. Import `Task` tables:

```bash
php artisan migrate
```

### 5. Fixture testing with PHPUnit:

```bash
./vendor/bin/phpunit
```

### 6. Testing different user and admin settings

You can change test user and admin settings in .env file

```bash
vi .env

TEST_USERNAME=Denny
TEST_IS_ADMIN=0  #0 or 1
```

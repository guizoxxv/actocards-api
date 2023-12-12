# ACTOCARDS API

Backend for a cards game.

> Frontend in https://github.com/guizoxxv/actocards-app

## Tools
* [PHP](https://www.php.net/) v8.0.2
* [Laravel](https://laravel.com/) v8.37.0
* [PostgreSQL](https://www.postgresql.org/) v13.2
* [Docker](https://www.docker.com/) v20.10.6
* [docker-compose](https://docs.docker.com/compose/) v1.28.6
* [Composer](https://getcomposer.org/) v2.0.11

## Installation

### Using local machine

1. Clone or download this repository to your machine

2. Create two databases in `PostgreSQL`: `actocards` and `actocards_test`

> `actocards_test` will be used for testing and must be referenced in the `.env.testing` file in `DB_DATABASE_TEST`

3. Copy the content of `.env.example` to a new file `.env`

```console
cp .env.example .env
```

4. Provide your databases information to the `DATABASE` section in the `.env` and `.env.testing`

5. Follow the steps in the `Common` section bellow

6. Start the application

```console
php artisan serve
```

### Using docker

1. Clone or download this repository to your machine

2. Create the containers

```console
docker-compose up -d
```

> The application will run on host port 8001 and the database on port 3001 by default.

3. Access the `app` container

```console
docker-compose exec app bash
```

4. Copy the content of `.env.docker` to a new file `.env`

```console
cp .env.docker .env
```

5. Follow the steps in the `Common` section bellow

### Common

1. Install the dependencies

```console
composer install
```

2. Generate the application key (used for cookies)

```console
php artisan key:generate
```

3. Run the migrations (create tables)

```console
php artisan migrate
```

6. Set permissions to write in the storage folder

```console
sudo chmod -R 777 storage
```

> Avoid using 777 permission in production.

## Testing

Execute the following command in this project root directory to run the tests:

```console
php artisan test
```

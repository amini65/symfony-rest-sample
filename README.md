# Symfony 6 REST API

Building Symfony RESTful APIs using the Symfony 6.

## Features

- Symfony
- API Platform
- Docker integration
- Nginx and PHP-FPM setup
- PostgreSQL database

## Prerequisites

Before you begin, ensure you have met the following requirements:

- Docker and Docker Compose installed
- Basic knowledge of Symfony, Docker, and PostgreSQL

## Installation

Clone the repository:

```bash
git clone https://github.com/amini65/symfony-rest-sample.git

cd symfony-rest-sample
```

## Running the Application
To start the application, run the following commands:

```
docker-compose up -d
```

This will build and start the containers in the background.

Run this command to install all the dependencies using composer.

``` bash
docker compose run --rm composer install
```

## Database Setup and Migrations

The default development database is configured in the docker-compose.yml file.

To set up and migrate your database, run:

```
docker-compose exec php bin/console doctrine:migrations:migrate
```

This will apply the migrations to your database.

To create a test database add your test database initialization script to ./docker/postgres/init/init-db.sql.

The Docker PostgreSQL service will automatically create the test database on startup.

> By default, task_test database will be created.

To apply migrations to your test database, run:

```
docker-compose exec php bin/console doctrine:migrations:migrate --env=test
```

## API Endpoints

### Cars
`GET` /api/cars - List all cars

`POST` /api/cars - Create a new car

`GET` /api/cars/{id} - Get a specific car

`PUT` /api/cars/{id} - Update a specific car

`DELETE` /api/cars/{id} - Delete a specific car

### Reviews
`GET` /api/reviews - List all reviews

`POST` /api/reviews - Create a new review

`GET` /api/reviews/{id} - Get a specific review

`PUT` /api/reviews/{id} - Update a specific review

`DELETE` /api/reviews/{id} - Delete a specific review

### Custom Endpoints
`GET` /api/cars/{id}/latest_reviews - Get the latest five reviews for a specific car

## Testing

Tests are written using PHPUnit. Run tests with the following command:

```
docker-compose exec php bin/phpunit
```

## Postman Collection (API Documentation)

In the root directory, there is a file named `postman_collection.json` that you can import in postman application.


# Todo App API

A REST API for task management built with Laravel, featuring user authentication, tasks with tags, and advanced filtering capabilities.

## Features

-   **Authentication**: Complete user authentication with registration, login, email verification, and password reset
-   **Task Management**: Create, read, update, and delete tasks with support for priorities, statuses, and due dates
-   **Tagging System**: Categorize tasks with a flexible tagging system including color-coding
-   **Advanced Filtering**: Filter tasks by status, priority, due date, tags, and search content
-   **API Documentation**: Interactive API documentation using Swagger/OpenAPI

## Technology Stack

-   **Backend**: Laravel 12
-   **Authentication**: Laravel Sanctum
-   **Database**: MySQL
-   **Documentation**: L5 Swagger

## Getting Started

### Prerequisites

-   PHP 8.2 or higher
-   Composer
-   MySQL
-   Git

### Installation (Without Docker)

1. Clone the repository:

    ```bash
    git clone https://github.com/your-username/todo-app.git
    cd todo-app
    ```

2. Install dependencies:

    ```bash
    composer install
    ```

3. Create and configure your `.env` file:

    ```bash
    cp .env.example .env
    # Update the database connection settings in .env
    ```

4. Generate application key:

    ```bash
    php artisan key:generate
    ```

5. Run migrations and seed the database:

    ```bash
    php artisan migrate --seed
    ```

6. Start the development server:
    ```bash
    php artisan serve
    ```

### Docker Setup

1. Clone the repository:

    ```bash
    git clone https://github.com/your-username/todo-app.git
    cd todo-app
    ```

2. Create a `.env` file with your configuration (or use the provided one).

3. Start the Docker containers:

    ```bash
    docker-compose up -d
    ```

4. Set up the application:

    ```bash
    docker-compose exec app ./docker-entrypoint.sh
    ```

5. Access the application:
    - API: http://localhost:8000/api
    - API Documentation: http://localhost:8000/api/documentation

### Default Test User

-   Email: test@example.com
-   Password: password

## API Documentation

API documentation is available at `/api/documentation` when the application is running.

## API Endpoints

### Authentication

| Method | Endpoint                      | Description                 |
| ------ | ----------------------------- | --------------------------- |
| POST   | /api/auth/register            | Register a new user         |
| POST   | /api/auth/login               | Authenticate and get token  |
| POST   | /api/auth/logout              | Logout (invalidate token)   |
| GET    | /api/auth/user                | Get authenticated user info |
| GET    | /api/email/verify/{id}/{hash} | Verify email address        |
| POST   | /api/email/resend             | Resend verification email   |
| POST   | /api/auth/forgot-password     | Send password reset link    |
| POST   | /api/auth/reset-password      | Reset password              |

### Tasks

| Method | Endpoint        | Description                            |
| ------ | --------------- | -------------------------------------- |
| GET    | /api/tasks      | Get all tasks (with filtering options) |
| POST   | /api/tasks      | Create a new task                      |
| GET    | /api/tasks/{id} | Get a specific task                    |
| PUT    | /api/tasks/{id} | Update a task                          |
| DELETE | /api/tasks/{id} | Delete a task                          |

#### Task Filtering Options

-   `status`: Filter by status (pending, in_progress, completed)
-   `priority`: Filter by priority (low, medium, high)
-   `tag_id`: Filter by tag ID
-   `due_date`: Filter by due date (YYYY-MM-DD)
-   `overdue`: Filter overdue tasks (1 for overdue, 0 for not)
-   `search`: Search tasks by title or description
-   `per_page`: Number of tasks per page

### Tags

| Method | Endpoint       | Description        |
| ------ | -------------- | ------------------ |
| GET    | /api/tags      | Get all tags       |
| POST   | /api/tags      | Create a new tag   |
| GET    | /api/tags/{id} | Get a specific tag |
| PUT    | /api/tags/{id} | Update a tag       |
| DELETE | /api/tags/{id} | Delete a tag       |

## Database Schema

The application uses the following database tables:

-   `users`: User accounts
-   `tasks`: Task items
-   `tags`: Task categories/tags
-   `tag_task`: Many-to-many pivot table between tasks and tags
-   `password_reset_tokens`: For password reset functionality
-   `personal_access_tokens`: For API authentication

## Database Operations

### Seed the Database

The application comes with predefined seeders that create sample data for testing:

```bash
# Seed the database with sample data
php artisan db:seed

# Seed a specific table
php artisan db:seed --class=UsersTableSeeder
php artisan db:seed --class=TagsTableSeeder
php artisan db:seed --class=TasksTableSeeder

# In Docker
docker-compose exec app php artisan db:seed
```

### Reset and Clean Database

```bash
# Reset the database (drop all tables and re-run all migrations)
php artisan migrate:fresh

# Reset and seed in one command
php artisan migrate:fresh --seed

# In Docker
docker-compose exec app php artisan migrate:fresh --seed
```

### Creating Database Dumps

```bash
# Using mysqldump (outside Docker)
mysqldump -u username -p database_name > dump.sql

# Using mysqldump (with Docker)
docker-compose exec db mysqldump -u avnadmin -p defaultdb > dump.sql

# Creating a compressed dump
mysqldump -u username -p database_name | gzip > dump.sql.gz
```

### Restoring from Database Dumps

```bash
# Restore from a dump file (outside Docker)
mysql -u username -p database_name < dump.sql

# Restore in Docker
cat dump.sql | docker-compose exec -T db mysql -u avnadmin -p defaultdb

# Restore from a compressed dump
zcat dump.sql.gz | mysql -u username -p database_name
```

### Database Migrations

```bash
# Run migrations
php artisan migrate

# Run migrations with output
php artisan migrate --verbose

# Create a new migration
php artisan make:migration create_new_table

# Roll back the last migration
php artisan migrate:rollback

# Roll back all migrations
php artisan migrate:reset

# In Docker
docker-compose exec app php artisan migrate
```

## Development

### Generating API Documentation

```bash
php artisan l5-swagger:generate
```

## Postman Collection

A Postman collection is included in the repository for testing the API endpoints. Import the `postman.json` file into Postman to get started quickly.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Contact

For any questions or suggestions, please open an issue in the GitHub repository.

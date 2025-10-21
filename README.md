# Workopia — Job Listing Application

A minimal PHP job-listing web application built with a small custom framework.

## Quick overview

- Entry point: `public/index.php` — boots the app, starts session and routes requests using `Framework\Router`.
- Routes are defined in `routes.php`.
- Views are in `App/views/`.
- Controllers are in `App/controllers/` (e.g. `HomeController`, `ListingController`, `UserController`).
- Core framework classes are in `Framework/` (e.g. `Database`, `Session`, `Router`, `Validation`, `Authorization`).
- Helper utilities are in `helpers.php`.
- Composer autoload config is in `composer.json`.

## Requirements

- PHP 7.4 or newer
- PDO extension and MySQL
- Composer
- A web server with document root pointed to the `public/` folder (Apache, Nginx, or PHP built-in server)

## Installation

1. Clone or copy this repository into your web directory.

2. Install dependencies with Composer:

```bash
composer install
```

3. Create or update the database config at `config/db.php` with your database credentials. The project expects a PDO-compatible config. Example (not included):

```php
<?php
return [
    'host' => '127.0.0.1',
    'port' => 3306,
    'database' => 'workopia',
    'username' => 'root',
    'password' => '',
];
```

4. Make sure PHP sessions are writable and the web server's document root is set to the `public/` folder.

## Run (development)

From the project root you can use PHP's built-in server:

```bash
php -S localhost:8000 -t public
```

Open http://localhost:8000 in your browser.

## Routes

Routes are defined in `routes.php`. Typical routes include:

- `/` → `HomeController@index`
- `/listings` → `ListingController@index`
- `/listings/create` → `ListingController@create`
- `/listings/{id}` → `ListingController@show`
- `/auth/register`, `/auth/login`, `/auth/logout` → `UserController`

Middleware (for authorization) is provided by `Framework/middleware/Authorize.php` and used in route definitions.

## Project structure (important files)

- `public/` — front controller and public assets
  - `index.php` — front controller
  - `css/`, `images/`
- `App/`
  - `controllers/` — controllers
  - `views/` — templates and partials
- `Framework/` — lightweight framework classes (Router, Database, Session, Validation, Authorization)
- `helpers.php` — global helper functions
- `config/` — configuration files (e.g., `db.php`)

## Conventions

- Views expect small PHP templates under `App/views/` and partials in `App/views/partials/`.
- Controllers return views using the helper functions provided in `helpers.php`.
- Sessions and flash messages are managed by `Framework\Session`.

## Security & Deployment Notes

- Keep `config/db.php` out of version control when it contains credentials.
- Serve the `public/` folder as the web root in production.
- Use prepared statements (the project uses PDO) and validate/sanitize user input before use.

## Troubleshooting

- DB connection errors: verify `config/db.php` and credentials. Check `Framework/Database.php` for connection handling.
- 404/500 errors: see `App/controllers/ErrorController.php` and `App/views/error.php`.
- Session issues: ensure `Session::start()` is being called (it is in `public/index.php`) and that PHP session storage is writeable.

## Contributing

Small project — open a PR with clear description and tests (where applicable). Follow PSR-12 coding style where possible.

## License

No license file included. Add a LICENSE if you plan to open-source this project.

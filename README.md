# Laravel Schema To Form

Automatically generate **Blade** forms and **FormRequest** classes from your database schemas or JSON schema files.

<br>

## Package Name

`laravel-schema-to-form`

Composer: `forphp`

GitHub: [mbparvezme](https://github.com/mbparvezme)

Author: [M B Parvez](https://mbparvez.me)

<br>

## Features

- Generate JSON schemas from database tables.
- Generate Blade forms automatically with:
  - `required` attributes
  - Default values
  - Correct input types (`text`, `email`, `checkbox`, `textarea`, etc.)
- Generate Laravel FormRequest classes with proper validation rules.
- Fully customizable templates for Blade and FormRequest.
- Configurable FormRequest namespace and Blade output directory.

<br>

## Installation
Install via Composer:
```sh
composer require forphp/laravel-schema-to-form
```
Laravel will auto-discover the package.

<br>

## Publish Templates and Config
Publish templates and config if you want to customize:
```sh
php artisan vendor:publish --tag=form-schema-templates
php artisan vendor:publish --tag=form-schema-config
```
- Templates: `resources/form-schema-templates`
- Config: `config/form-schema.php`

<br>

## Commands

### 1. Generate JSON from DB Tables
```sh
php artisan form:generate-json
```

Options:
- `--tables=users,posts` → generate JSON only for selected tables
- `--output=custom/path` → save JSON files in a custom folder

<br>

### 2. Generate Blade Forms and FormRequest
```sh
php artisan form:generate-form
```

Options:
- `schema` → optional, path to a single JSON schema file
- `--output=resources/views/custom_forms` → optional, save Blade forms in a custom folder

<br>

### 3. Generate JSON + Form Together
```sh
php artisan form:generate
```

This core command runs both `form:generate-json` and `form:generate-form` in sequence.
Use it to generate everything (JSON schemas, Blade forms, and FormRequest classes) at once.

<br>

## Example Workflow

### 1. Generate JSON from database tables:
```sh
php artisan form:generate-json --tables=users,posts
```

This will create JSON files in `database/form_schemas/`.

<br>

### 2. Generate Blade forms and FormRequests from JSON:
```sh
php artisan form:generate-form
```

This will generate:
- Blade files in `resources/views/forms/`
- FormRequest classes in `app/Http/Requests/`

<br>

### 3. Generate Both JSON and Forms in One Step:
```sh
php artisan form:generate
```

This will automatically:
- Generate JSON schemas from the database
- Generate Blade forms and FormRequests

<br>

## Customizing Templates

After publishing, you can edit:

- `resources/form-schema-templates/form.blade.php.template` → for custom Blade layout
- `resources/form-schema-templates/request.php.template` → for custom FormRequest template

<br>

## Config Options
```php
return [
    'request_namespace' => 'App\\Http\\Requests', // FormRequest namespace
    'blade_path' => resource_path('views/forms'), // Blade output path
];
```
<br>

## Contribution
Contributions are welcome! If you have ideas for improvement, bug fixes, or want to add new features:

1. Fork the repository
2. Create a new branch (`feature/new-feature`)
3. Commit your changes
4. Push to your branch
5. Open a pull request

<br>

## License
[MIT License](https://github.com/mbparvezme/laravel-schema-to-form?tab=MIT-1-ov-file)

# Laravel Schema To Form

Automatically generate **Blade forms** and **Form request** classes from your database schemas or JSON schema files with `old()` support and built-in CSS styling.

<br>

## Package Name

`laravel-schema-to-form`

Composer: `mbparvez`

GitHub: [mbparvezme](https://github.com/mbparvezme)

Author: [M B Parvez](https://mbparvez.me)

<br>

## Features

- Generate JSON schemas from database tables.
- Generate Blade forms automatically with:
  - `old()` function for preserving input values
  - `required` attributes
  - Default values
  - Correct input types (`text`, `email`, `checkbox`, `textarea`, etc.)
  - Built-in CSS classes (Bootstrap or custom)
- Generate Laravel **FormRequest** classes with proper validation rules.
- Fully customizable templates for Blade and FormRequest.
- Configurable FormRequest namespace and Blade output directory.

<br>

## Installation
Install via Composer:
```sh
composer require mphp/laravel-db2form
```
Laravel will auto-discover the package. No extra steps needed to use it.

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
- `--schema=path/to/schema.json` → generate form for a single schema file
- `--output=resources/views/custom_forms` → save Blade files in a custom folder

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
- Input fields automatically include old() values and CSS classes

<br>

### 3. Generate Both JSON and Forms in One Step:
```sh
php artisan form:generate
```

This will automatically:
- Generate JSON schemas from the database
- Generate Blade forms and FormRequests

<br>

## Publish Templates and Config (Optional)
Publish templates and config if you want to customize:
```sh
php artisan vendor:publish --tag=form-schema-templates
php artisan vendor:publish --tag=form-schema-config
```
- Templates: `resources/form-schema-templates`
- Config: `config/form-schema.php`

<br>

## Configuration

`config/form-schema.php`:

```php
return [
    /*
    |----------------------------------------------------------------------
    | FormRequest Namespace
    |----------------------------------------------------------------------
    */
    'request_namespace' => 'App\\Http\\Requests',

    /*
    |----------------------------------------------------------------------
    | Blade Output Path
    |----------------------------------------------------------------------
    */
    'blade_path' => resource_path('views/forms'),

    /*
    |----------------------------------------------------------------------
    | Style Configuration
    |----------------------------------------------------------------------
    | 'style' => 'bootstrap' or 'custom'
    */
    'style' => 'bootstrap', // default style

    'bootstrap_styles' => [
        'form' => 'needs-validation',
        'submit' => 'btn btn-primary',
        'label' => 'form-label',
        'text' => 'form-control',
        'email' => 'form-control',
        'password' => 'form-control',
        'number' => 'form-control',
        'textarea' => 'form-control',
        'select' => 'form-select',
        'checkbox' => 'form-check-input',
        'radio' => 'form-check-input',
    ],

    'custom_styles' => [
        'form' => 'space-y-4',
        'submit' => 'bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700',
        'label' => 'block font-medium mb-1',
        'text' => 'border rounded px-3 py-2 w-full',
        'email' => 'border rounded px-3 py-2 w-full',
        'password' => 'border rounded px-3 py-2 w-full',
        'number' => 'border rounded px-3 py-2 w-full',
        'textarea' => 'border rounded px-3 py-2 w-full',
        'select' => 'border rounded px-3 py-2 w-full',
        'checkbox' => 'rounded text-blue-600 focus:ring-blue-500',
        'radio' => 'text-blue-600 focus:ring-blue-500',
    ],
];

```

<br>

## Customizing Templates

After publishing, you can edit:

- `resources/form-schema-templates/form.blade.php.template` → for custom Blade layout
- `resources/form-schema-templates/request.php.template` → for custom FormRequest template

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
[MIT License](https://github.com/mbparvezme/laravel-db2form?tab=MIT-1-ov-file)

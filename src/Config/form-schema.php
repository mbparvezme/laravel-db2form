<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default FormRequest Namespace
    |--------------------------------------------------------------------------
    |
    | Specify the namespace where FormRequest classes will be generated.
    |
    */
    'request_namespace' => 'App\\Http\\Requests',

    /*
    |--------------------------------------------------------------------------
    | Default Blade Output Directory
    |--------------------------------------------------------------------------
    |
    | Specify the default directory where Blade forms will be saved.
    |
    */
    'blade_path' => resource_path('views/forms'),

    /*
    |--------------------------------------------------------------------------
    | Form Style
    |--------------------------------------------------------------------------
    |
    | Choose your form style preset:
    | - 'bootstrap' : Adds Bootstrap-compatible classes automatically.
    | - 'custom'    : Use your own CSS classes defined below.
    |
    */
    'style' => 'custom',

    /*
    |--------------------------------------------------------------------------
    | Bootstrap Default Styles
    |--------------------------------------------------------------------------
    |
    | These styles will be applied automatically if 'style' is set to 'bootstrap'.
    |
    */

    'bootstrap_styles' => [
        'form' => 'mb-3',
        'label' => 'form-label',
        'text' => 'form-control',
        'email' => 'form-control',
        'password' => 'form-control',
        'number' => 'form-control',
        'textarea' => 'form-control',
        'select' => 'form-select',
        'checkbox' => 'form-check-input',
        'radio' => 'form-check-input',
        'submit' => 'btn btn-primary',
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Styles
    |--------------------------------------------------------------------------
    |
    | Define your own CSS classes when 'style' is set to 'custom'.
    | You can use TailwindCSS or any other CSS framework here.
    |
    */

    'custom_styles' => [
        'form' => 'space-y-4',
        'label' => 'block font-medium mb-1',
        'text' => 'border rounded px-3 py-2 w-full',
        'email' => 'border rounded px-3 py-2 w-full',
        'password' => 'border rounded px-3 py-2 w-full',
        'number' => 'border rounded px-3 py-2 w-full',
        'textarea' => 'border rounded px-3 py-2 w-full',
        'select' => 'border rounded px-3 py-2 w-full',
        'checkbox' => 'rounded text-blue-600 focus:ring-blue-500',
        'radio' => 'text-blue-600 focus:ring-blue-500',
        'submit' => 'bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700',
    ],

];

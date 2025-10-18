<?php

namespace Forphp\LaravelSchemaToForm;

use Illuminate\Support\ServiceProvider;
use Forphp\LaravelSchemaToForm\Commands\GenerateFormCommand;
use Forphp\LaravelSchemaToForm\Commands\GenerateJsonCommand;
use Forphp\LaravelSchemaToForm\Commands\GenerateAllCommand;

class FormSchemaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Works with the default style
        $this->mergeConfigFrom(__DIR__ . '/Config/form-schema.php', 'form-schema');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            // Register commands
            $this->commands([
                GenerateFormCommand::class, // php artisan form:generate-json
                GenerateJsonCommand::class, // php artisan form:generate-form
                GenerateAllCommand::class,  // php artisan form:generate
            ]);

            // Publish resources
            $this->publishes([
                __DIR__ . '/Templates' => resource_path('form-schema-templates'),
            ], 'form-schema-templates');

            $this->publishes([
                __DIR__ . '/Config/form-schema.php' => config_path('form-schema.php'),
            ], 'form-schema-config');
        }
    }
}

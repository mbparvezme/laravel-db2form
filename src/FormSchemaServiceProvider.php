<?php

namespace Forphp\LaravelSchemaToForm;

use Illuminate\Support\ServiceProvider;
use Forphp\LaravelSchemaToForm\Commands\GenerateFormCommand;
use Forphp\LaravelSchemaToForm\Commands\GenerateJsonCommand;

class FormSchemaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->commands([
            GenerateFormCommand::class,
            GenerateJsonCommand::class,
        ]);
    }

    public function boot(): void
    {
        $this->publishes(
            [__DIR__ . '/Templates' => resource_path('form-schema-templates')],
            'form-schema-templates'
        );

        $this->publishes(
            [__DIR__ . '/Config/form-schema.php' => config_path('form-schema.php')],
            'form-schema-config'
        );
    }
}

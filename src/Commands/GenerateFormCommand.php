<?php

namespace Forphp\LaravelSchemaToForm\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Forphp\LaravelSchemaToForm\Helpers\SchemaParser;
use Forphp\LaravelSchemaToForm\Generators\BladeGenerator;
use Forphp\LaravelSchemaToForm\Generators\RequestGenerator;

class GenerateFormCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'form:generate-form
                            {schema? : Path to a single JSON schema file}
                            {--output= : Directory to save generated files}';

    /**
     * The console command description.
     */
    protected $description = 'Generate Blade forms and FormRequest classes from JSON schema';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $schemaPath = $this->argument('schema');

        // $outputDir = $this->option('output') ?? base_path('resources/views/forms');
        // Added later
        $outputDir = $this->option('output') ?? Config::get('form-schema.blade_path', resource_path('views/forms'));

        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        // Determine schema files to process
        $schemaFiles = $schemaPath
            ? [$schemaPath]
            : File::files(database_path('form_schemas'));

        foreach ($schemaFiles as $file) {
            $json = json_decode(File::get($file), true);
            if (!$json) {
                $this->warn("Invalid JSON: {$file}");
                continue;
            }

            $schema = new SchemaParser($json);

            // Generate Blade form
            $bladeGenerator = new BladeGenerator($schema, $outputDir);
            $bladeFile = $bladeGenerator->generate();
            $this->info("Blade form generated: {$bladeFile}");

            // Generate FormRequest
            $requestNamespace = Config::get('form-schema.request_namespace', 'App\\Http\\Requests');
            $requestGenerator = new RequestGenerator($schema, app_path('Http/Requests'), $requestNamespace);

            $requestFile = $requestGenerator->generate();
            $this->info("FormRequest generated: {$requestFile}");
        }

        $this->info('All forms and FormRequests generated successfully.');
        return Command::SUCCESS;
    }
}

<?php

namespace Forphp\LaravelSchemaToForm\Commands;

use Illuminate\Console\Command;

class GenerateAllCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'form:generate';

    /**
     * The console command description.
     */
    protected $description = 'Generate: first JSON schema, then Blade form templates.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Running form:generate-json...');
        $this->call('form:generate-json');

        $this->info('Running form:generate-form...');
        $this->call('form:generate-form');

        $this->info('✅ All JSON and forms generated successfully!');
        return self::SUCCESS;
    }
}

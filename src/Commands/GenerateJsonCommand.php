<?php

namespace Forphp\LaravelSchemaToForm\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Forphp\LaravelSchemaToForm\Helpers\SchemaParser;

class GenerateJsonCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'form:generate-json 
                            {--tables= : Comma-separated list of tables to generate JSON for} 
                            {--output= : Directory to save JSON files}';

    /**
     * The console command description.
     */
    protected $description = 'Generate JSON schema files from database tables';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $tablesOption = $this->option('tables');
        $outputDir = $this->option('output') ?? database_path('form_schemas');

        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        // Fetch all tables if not specified
        $tables = $tablesOption ? explode(',', $tablesOption) : $this->getAllTables();

        foreach ($tables as $table) {
            $columns = $this->getTableColumns($table);

            if (empty($columns)) {
                $this->warn("Table '{$table}' has no columns. Skipping.");
                continue;
            }

            $schemaArray = SchemaParser::fromDb($table, $columns);

            $jsonFile = $outputDir . '/' . $table . '.json';
            File::put($jsonFile, json_encode($schemaArray, JSON_PRETTY_PRINT));

            $this->info("JSON schema generated: {$jsonFile}");
        }

        $this->info('All JSON schemas generated successfully.');
        return Command::SUCCESS;
    }

    /**
     * Get all table names in the current database.
     */
    protected function getAllTables(): array
    {
        $databaseName = DB::getDatabaseName();
        $tables = DB::select("SHOW TABLES");
        $key = "Tables_in_{$databaseName}";
        return array_map(fn($t) => $t->$key, $tables);
    }

    /**
     * Get columns info for a given table.
     */
    protected function getTableColumns(string $table): array
    {
        $columns = DB::select("SHOW FULL COLUMNS FROM `{$table}`");
        $result = [];

        foreach ($columns as $col) {
            $result[] = [
                'name' => $col->Field,
                'type' => $col->Type,
                'nullable' => $col->Null === 'YES',
                'default' => $col->Default,
            ];
        }

        return $result;
    }
}

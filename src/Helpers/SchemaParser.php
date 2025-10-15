<?php

namespace Forphp\LaravelSchemaToForm\Helpers;

use Illuminate\Support\Str;

class SchemaParser
{
    public string $title;
    public string $method;
    public array $fields = [];

    /**
     * Constructor: Accepts either JSON array or DB table array
     *
     * @param array $schemaData
     */
    public function __construct(array $schemaData)
    {
        $this->title = $schemaData['title'] ?? 'Form';
        $this->method = $schemaData['method'] ?? 'POST';
        $this->fields = $schemaData['fields'] ?? [];
    }

    /**
     * Static method: Parse DB columns into schema array
     *
     * @param string $tableName
     * @param array $columns
     * @return array
     */
    public static function fromDb(string $tableName, array $columns): array
    {
      $ignored = ['id', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by'];
      $fields = [];

      foreach ($columns as $col) {
          if (in_array($col['name'], $ignored)) {
              // Skip system fields
              continue;
          }

          // Determine input type based on column type
          $type = match ($col['type']) {
              'string', 'varchar' => 'text',
              'text' => 'textarea',
              'email' => 'email',
              'boolean', 'tinyint' => 'checkbox',
              'integer', 'bigint' => 'number',
              'date' => 'date',
              'datetime', 'timestamp' => 'datetime-local',
              default => 'text',
          };

          // Determine validation rules
          $rules = [];
          if (!$col['nullable']) {
              $rules[] = 'required';
          }

          // Type-based validation
          if (Str::contains($type, ['email'])) {
              $rules[] = 'email';
          } elseif (Str::contains($type, ['number'])) {
              $rules[] = 'numeric';
          }

          $field = [
              'name' => $col['name'],
              'type' => $type,
              'label' => Str::title(str_replace('_', ' ', $col['name'])),
              'rules' => implode('|', $rules),
              'default' => $col['default'] ?? null,
          ];

          $fields[] = $field;
        }

        return [
            'title' => Str::title(str_replace('_', ' ', $tableName)),
            'method' => 'POST',
            'fields' => $fields,
        ];
    }
}

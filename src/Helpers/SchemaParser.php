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
                continue;
            }

            $name = strtolower($col['name']);
            $colType = strtolower($col['type']);

            // Determine input type
            $type = match (true) {
                str_contains($name, 'email') => 'email',
                str_contains($name, 'password') => 'password',
                str_contains($name, 'image') || str_contains($name, 'photo') || str_contains($name, 'avatar') => 'file',
                str_contains($name, 'file') || str_contains($name, 'attachment') => 'file',
                str_contains($name, 'url') => 'url',
                str_contains($name, 'phone') || str_contains($name, 'mobile') => 'tel',
                $colType === 'text' => 'textarea',
                $colType === 'boolean' || $colType === 'tinyint' => 'checkbox',
                in_array($colType, ['integer', 'bigint', 'smallint', 'decimal', 'float', 'double']) => 'number',
                $colType === 'date' => 'date',
                in_array($colType, ['datetime', 'timestamp']) => 'datetime-local',
                $colType === 'enum' => 'select',
                $colType === 'json' => 'textarea',
                default => 'text',
            };

            // Auto-detect ENUM options
            $options = [];
            if ($colType === 'enum' && isset($col['type_definition'])) {
                // type_definition example: "enum('male','female','other')"
                preg_match_all("/'([^']+)'/", $col['type_definition'], $matches);
                if (!empty($matches[1])) {
                    $options = $matches[1];
                }
            }

            // Validation rules
            $rules = [];
            if (!$col['nullable']) {
                $rules[] = 'required';
            }
            if (Str::contains($type, 'email')) {
                $rules[] = 'email';
            } elseif (Str::contains($type, 'number')) {
                $rules[] = 'numeric';
            }

            $fields[] = [
                'name' => $col['name'],
                'type' => $type,
                'label' => Str::title(str_replace('_', ' ', $col['name'])),
                'rules' => implode('|', $rules),
                'default' => $col['default'] ?? null,
                'options' => $options,
            ];
        }

        return [
            'title' => Str::title(str_replace('_', ' ', $tableName)),
            'method' => 'POST',
            'fields' => $fields,
        ];
    }

}

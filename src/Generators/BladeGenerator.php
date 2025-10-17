<?php

namespace Forphp\LaravelSchemaToForm\Generators;

use Forphp\LaravelSchemaToForm\Helpers\SchemaParser;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BladeGenerator
{
  protected SchemaParser $schema;
  protected string $outputDir;

  /**
   * Constructor
   */
  public function __construct(SchemaParser $schema, string $outputDir)
  {
    $this->schema = $schema;
    $this->outputDir = rtrim($outputDir, '/');
  }

  /**
   * Generate the Blade file
   */
  public function generate(): string
  {
    $fieldsHtml = '';

    foreach ($this->schema->fields as $field) {
      $fieldsHtml .= $this->generateField($field) . "\n\n";
    }

    // Load Blade template
    $templatePath = __DIR__ . '/../Templates/form.blade.php.template';
    $template = File::get($templatePath);

    // Replace placeholders
    $template = str_replace('{{title}}', $this->schema->title, $template);
    $template = str_replace('{{method}}', $this->schema->method, $template);
    $template = str_replace('{{fields}}', $fieldsHtml, $template);

    $fileName = $this->outputDir . '/' . Str::kebab($this->schema->title) . '.blade.php';
    File::put($fileName, $template);

    return $fileName;
  }

  /**
   * Generate individual field HTML
   */
  protected function generateField(array $field): string
  {
    $required = Str::contains($field['rules'], 'required') ? 'required' : '';
    $defaultValue = $field['default'] ?? '';
    $label = $field['label'] ?? ucfirst($field['name']);
    $name = $field['name'];

    switch ($field['type']) {
      case 'textarea':
        return <<<HTML
<label for="{$name}">{$label}</label>
<textarea name="{$name}" id="{$name}" {$required}>{$defaultValue}</textarea>
HTML;

      case 'checkbox':
        $checked = $defaultValue ? 'checked' : '';
        return <<<HTML
<label>
    <input type="checkbox" name="{$name}" id="{$name}" {$required} {$checked}>
    {$label}
</label>
HTML;

      default: // text, email, number, date, etc.
        return <<<HTML
<label for="{$name}">{$label}</label>
<input type="{$field['type']}" name="{$name}" id="{$name}" value="{$defaultValue}" {$required}>
HTML;
    }
  }
}

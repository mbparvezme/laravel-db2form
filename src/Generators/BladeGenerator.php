<?php

namespace Forphp\LaravelSchemaToForm\Generators;

use Forphp\LaravelSchemaToForm\Helpers\SchemaParser;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BladeGenerator
{
  protected SchemaParser $schema;
  protected string $outputDir;

  public function __construct(SchemaParser $schema, string $outputDir)
  {
    $this->schema = $schema;
    $this->outputDir = rtrim($outputDir, '/');
  }

  public function generate(): string
  {
    $styleType = Config::get('form-schema.style', 'bootstrap');
    $styles = $styleType === 'custom'
      ? Config::get('form-schema.custom_styles', [])
      : Config::get('form-schema.bootstrap_styles', []);

    $formClass = $styles['form'] ?? '';
    $submitClass = $styles['submit'] ?? '';

    $fieldsHtml = '';
    foreach ($this->schema->fields as $field) {
      $fieldsHtml .= $this->generateField($field, $styles) . "\n\n";
    }

    // Load Blade template
    $templatePath = __DIR__ . '/../Templates/form.blade.php.template';
    $template = File::get($templatePath);

    // Replace placeholders and inject form & submit classes
    $template = str_replace('{{title}}', $this->schema->title, $template);
    $template = str_replace('{{method}}', $this->schema->method, $template);
    $template = str_replace('{{fields}}', $fieldsHtml, $template);
    $template = str_replace('<form', "<form class=\"{$formClass}\"", $template);
    $template = str_replace('<button type="submit"', "<button type=\"submit\" class=\"{$submitClass}\"", $template);

    $fileName = $this->outputDir . '/' . Str::kebab($this->schema->title) . '.blade.php';
    File::put($fileName, $template);

    return $fileName;
  }

  protected function generateField(array $field, array $styles): string
  {
    $required = Str::contains($field['rules'], 'required') ? 'required' : '';
    $defaultValue = $field['default'] ?? '';
    $label = $field['label'] ?? ucfirst(str_replace('_', ' ', $field['name']));
    $name = $field['name'];
    $type = $field['type'] ?? 'text';

    $labelClass = $styles['label'] ?? '';
    $inputClass = $styles[$type] ?? ($styles['text'] ?? '');

    switch ($type) {
      case 'textarea':
        return <<<HTML
<div class="mb-3">
    <label for="{$name}" class="{$labelClass}">{$label}</label>
    <textarea name="{$name}" id="{$name}" class="{$inputClass}" {$required}>{{ old('{$name}', '{$defaultValue}') }}</textarea>
</div>
HTML;

      case 'checkbox':
      case 'radio':
        return <<<HTML
<div class="mb-3 form-check">
    <input type="{$type}" name="{$name}" id="{$name}" class="{$inputClass}" value="1" {{ old('{$name}') ? 'checked' : '' }} {$required}>
    <label for="{$name}" class="{$labelClass}">{$label}</label>
</div>
HTML;

      default: // text, email, number, date, password, etc.
        return <<<HTML
<div class="mb-3">
    <label for="{$name}" class="{$labelClass}">{$label}</label>
    <input type="{$type}" name="{$name}" id="{$name}" class="{$inputClass}" value="{{ old('{$name}', '{$defaultValue}') }}" {$required}>
</div>
HTML;
    }
  }
}

<?php

namespace Mphp\LaravelDb2Form\Generators;

use Mphp\LaravelDb2Form\Helpers\SchemaParser;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BladeGenerator
{
    protected SchemaParser $schema;
    protected string $outputDir;
    protected array $styles = [];

    public function __construct(SchemaParser $schema, string $outputDir)
    {
        $this->schema = $schema;
        $this->outputDir = rtrim($outputDir, '/');
        $this->styles = $this->loadStyle(); // Load styles on construct
    }

    public function generate(): string
    {
        $fieldsHtml = '';
        foreach ($this->schema->fields as $field) {
            $fieldsHtml .= $this->generateField($field) . "\n\n";
        }

        // Load Blade template
        $templatePath = __DIR__ . '/../Templates/form.blade.php.template';
        $template = File::get($templatePath);

        // Replace placeholders and inject form & submit classes
        $template = str_replace('{{title}}', $this->schema->title, $template);
        $template = str_replace('{{method}}', $this->schema->method, $template);
        $template = str_replace('{{fields}}', $fieldsHtml, $template);
        $formClass = $this->styles['form'] ?? '';
        $template = str_replace('<form', "<form class=\"{$formClass}\"", $template);
        $submitClass = $this->styles['submit'] ?? '';
        $template = str_replace('<button type="submit"', "<button type=\"submit\" class=\"{$submitClass}\"", $template);

        $fileName = $this->outputDir . '/' . Str::kebab($this->schema->title) . '.blade.php';
        File::put($fileName, $template);

        return $fileName;
    }

    protected function generateField(array $field): string
    {
        $required = Str::contains($field['rules'], 'required') ? 'required' : '';
        $defaultValue = $field['default'] ?? '';
        $label = $field['label'] ?? ucfirst(str_replace('_', ' ', $field['name']));
        $name = $field['name'];
        $type = $field['type'] ?? 'text';
        $options = $field['options'] ?? [];

        $labelClass = $this->styles['label'] ?? '';
        $inputClass = $this->styles[$type] ?? ($this->styles['text'] ?? '');

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

            case 'select':
                $optionsHtml = '';
                foreach ($options as $opt) {
                    $selected = "{{ old('{$name}', '{$defaultValue}') == '{$opt}' ? 'selected' : '' }}";
                    $optionsHtml .= "<option value=\"{$opt}\" {$selected}>{$opt}</option>\n";
                }
                return <<<HTML
<div class="mb-3">
    <label for="{$name}" class="{$labelClass}">{$label}</label>
    <select name="{$name}" id="{$name}" class="{$inputClass}" {$required}>
        {$optionsHtml}
    </select>
</div>
HTML;

            default: // text, email, number, date, password, file, etc.
                return <<<HTML
<div class="mb-3">
    <label for="{$name}" class="{$labelClass}">{$label}</label>
    <input type="{$type}" name="{$name}" id="{$name}" class="{$inputClass}" value="{{ old('{$name}', '{$defaultValue}') }}" {$required}>
</div>
HTML;
        }
    }

    protected function loadStyle(): array
    {
        $styleType = Config::get('form-schema.style', 'bootstrap');
        return $styleType === 'custom'
            ? Config::get('form-schema.custom_styles', [])
            : Config::get('form-schema.bootstrap_styles', []);
    }
}

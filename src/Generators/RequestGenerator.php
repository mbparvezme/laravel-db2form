<?php

namespace Mphp\LaravelDb2Form\Generators;

use Mphp\LaravelDb2Form\Helpers\SchemaParser;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RequestGenerator
{
    protected SchemaParser $schema;
    protected string $outputDir;
    protected string $namespace;

  /**
   * Constructor
   */
  public function __construct(SchemaParser $schema, string $outputDir, string $namespace = 'App\\Http\\Requests')
  {
      $this->schema = $schema;
      $this->outputDir = rtrim($outputDir, '/');
      $this->namespace = $namespace;

      if (!File::exists($this->outputDir)) {
          File::makeDirectory($this->outputDir, 0755, true);
      }
  }

  /**
   * Generate FormRequest class
   */
  public function generate(): string
  {
      $className = Str::studly($this->schema->title) . 'Request';
      $rulesArray = $this->buildRulesArray();

      // Load template
      $templatePath = __DIR__ . '/../Templates/request.php.template';
      $template = File::get($templatePath);

      // Replace placeholders
      $template = str_replace('{{namespace}}', $this->namespace, $template); // <-- add here
      $template = str_replace('{{class}}', $className, $template);
      $template = str_replace('{{rules}}', $rulesArray, $template);

      $fileName = $this->outputDir . '/' . $className . '.php';
      File::put($fileName, $template);

      return $fileName;
  }

  /**
   * Build rules array as PHP string
   */
  protected function buildRulesArray(): string
  {
      $rules = [];
      foreach ($this->schema->fields as $field) {
          $rules[] = "            '{$field['name']}' => '{$field['rules']}'";
      }

      return "[\n" . implode(",\n", $rules) . "\n        ]";
  }
}

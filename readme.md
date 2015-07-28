# Transform PHP Vars to JavaScript

This is forked version of https://github.com/laracasts/PHP-Vars-To-Js-Transformer

Goal of this fork: Remove framework and ViewBinder dependency to make it usable in any PHP projects.

####Changes

* Added set($key, $value)
* Replaced buildJavaScriptSyntax(array $vars) with build(array $vars). build(array $vars) collects variables set by set($key, $value) and return transformed javascript variables.

#### Usage

```php
use Moon\Utilities\Javascript\PHPToJavaScriptTransformer;

$transformer = new PHPToJavaScriptTransformer;

// example 1 
$javascript = $transformer->set('name', 'moon')
			->set('age', 999999)
            ->transform()

// example 2 
$javascript = $transformer->transform([
	'name' => 'moon',
    'age' => 999999
]);

// in your view

<script><?=$javascript?></script>

```


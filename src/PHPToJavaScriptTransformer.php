<?php

namespace Moon\Utilities\Javascript;

use Exception;

class PHPToJavaScriptTransformer
{
    /**
     * The namespace to nest JS vars under.
     *
     * @var string
     */
    protected $namespace;

    /**
     * All transformable types.
     *
     * @var array
     */
    protected $types = [
        'String',
        'Array',
        'Object',
        'Numeric',
        'Boolean',
        'Null',
    ];

    /**
     * Variables to be binded.
     *
     * @var array
     */
    protected $vars = [];

    /**
     * Create a new JS transformer instance.
     *
     * @param ViewBinder $viewBinder
     * @param string     $namespace
     */
    public function __construct($namespace = 'window')
    {
        $this->namespace = $namespace;
    }

    public function set($key, $value = null)
    {
        $this->vars[$key] = $value;

        return $this;
    }

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    public function count()
    {
        return count($this->vars);
    }

    /**
     * Bind the given array of variables to the view.
     *
     * @param array $variables
     */
    public function put(array $variables)
    {
        $this->vars = $variables;

        return $this;
    }

    /**
     * Translate the array of PHP vars to
     * the expected JavaScript syntax.
     *
     * @param array $vars
     *
     * @return array
     */
    public function transform(array $vars = [])
    {
        $js = $this->buildNamespaceDeclaration();

        $vars = array_merge($this->vars, $vars);

        foreach ($vars as $key => $value) {
            $js .= $this->buildVariableInitialization($key, $value);
        }

        return $js;
    }

    /**
     * Create the namespace that all
     * vars will be nested under.
     *
     * @return string
     */
    protected function buildNamespaceDeclaration()
    {
        return "window.{$this->namespace} = window.{$this->namespace} || {};";
    }

    /**
     * Translate a single PHP var to JS.
     *
     * @param string $key
     * @param string $value
     *
     * @return string
     */
    protected function buildVariableInitialization($key, $value)
    {
        return "{$this->namespace}.{$key} = {$this->optimizeValueForJavaScript($value)};";
    }

    /**
     * Format a value for JavaScript.
     *
     * @param string $value
     *
     * @throws \Exception
     *
     * @return mixed
     */
    protected function optimizeValueForJavaScript($value)
    {
        // For every transformable type, let's see if
        // it needs to be converted for JS-used.
        foreach ($this->types as $transformer) {
            $js = $this->{"transform{$transformer}"}($value);

            if (!is_null($js)) {
                return $js;
            }
        }
    }

    /**
     * Transform a string.
     *
     * @param string $value
     *
     * @return string
     */
    protected function transformString($value)
    {
        if (is_string($value)) {
            return "'{$this->escape($value)}'";
        }
    }

    /**
     * Transform an array.
     *
     * @param array $value
     *
     * @return string
     */
    protected function transformArray($value)
    {
        if (is_array($value)) {
            return json_encode($value);
        }
    }

    /**
     * Transform a numeric value.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    protected function transformNumeric($value)
    {
        if (is_numeric($value)) {
            return $value;
        }
    }

    /**
     * Transform a boolean.
     *
     * @param bool $value
     *
     * @return string
     */
    protected function transformBoolean($value)
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
    }

    /**
     * Transform an object.
     *
     * @param object $value
     *
     * @return string
     *
     * @throws \Exception
     */
    protected function transformObject($value)
    {
        if (is_object($value)) {
            // If a toJson() method exists, we'll assume that
            // the object can cast itself automatically.
            if (method_exists($value, 'toJson')) {
                return $value;
            }

            // Otherwise, if the object doesn't even have
            // a toString method, we can't proceed.
            if (!method_exists($value, '__toString')) {
                throw new Exception('The provided object needs a __toString() method.');
            }

            return "'{$value}'";
        }
    }

    /**
     * Transform "null.".
     *
     * @param mixed $value
     *
     * @return string
     */
    protected function transformNull($value)
    {
        if (is_null($value)) {
            return 'null';
        }
    }

    /**
     * Escape any single quotes.
     *
     * @param string $value
     *
     * @return string
     */
    protected function escape($value)
    {
        return str_replace(['\\', "'"], ['\\\\', "\'"], $value);
    }
}

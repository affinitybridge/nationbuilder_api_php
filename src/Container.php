<?php

namespace Affinitybridge\NationBuilder;

class Container
{
    protected $definitions = [];

    public function __construct(array $bulkInjections = [])
    {
        foreach ($bulkInjections as $name => $value) {
            $this->__set($name, $value);
        }
    }

    public function __set($name, $definition)
    {
        if (!is_object($definition) && !is_callable($definition) && !(is_string($definition) && class_exists($definition))) {
            throw new \Exception("Error: `{$definition}` is neither an object, nor callable, nor a class name.");
        }
        if ($definition instanceof \Closure) {
            $definition = $definition->bindTo($this, get_class($this));
        }
        $this->definitions[$name] = $definition;
    }

    public function __get($name)
    {
        if (!isset($this->definitions[$name])) {
            throw new \Exception("Error: Undefined dependency `{$name}`.");
        }

        $definition = $this->definitions[$name];

        if (is_string($definition) && class_exists($definition)) {
            return new $definition($this);
        } else if (is_callable($definition)) {
            return call_user_func_array($definition, [$this]);
        } else if (is_object($definition)) {
            if (method_exists($definition, 'setContainer')) {
                $definition->setContainer($this);
            }
            return $definition;
        }
    }

    public function __call($name, $arguments)
    {
        if (!isset($this->definitions[$name])) {
            throw new \Exception("Error: Undefined dependency `{$name}`.");
        }

        $definition = $this->definitions[$name];

        if (is_callable($definition)) {
            if (!isset($arguments[0]) || ($this != $arguments[0])) {
                array_unshift($arguments, $this);
            }
            return call_user_func_array($definition, $arguments);
        } else if (is_string($definition) && class_exists($definition)) {
            if (0 < count($arguments)) {
                if ($this != $arguments[0]) {
                    array_unshift($arguments, $this);
                }
                $refl = new \ReflectionClass($definition);
                return $refl->newInstanceArgs($arguments);
            }
            return new $definition($this);
        }

        throw new \Exception("Error: The `{$name}` dependency definition can not be called as a function.");
    }

    public function __isset($name)
    {
        return isset($this->definitions[$name]);
    }

    public function __unset($name)
    {
        unset($this->definitions[$name]);
    }
}

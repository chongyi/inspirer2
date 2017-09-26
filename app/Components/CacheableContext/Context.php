<?php
/**
 * Context.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Components\CacheableContext;


use ArrayIterator;
use IteratorAggregate;
use Serializable;
use Traversable;

/**
 * Class Context
 *
 * @package App\Components\CacheableContext
 */
class Context implements Serializable, IteratorAggregate
{
    protected $context;

    /**
     * @var string|ContextResolver
     */
    protected $resolver;

    /**
     * @param mixed $resolver
     *
     * @return Context
     */
    public function setResolver($resolver)
    {
        $this->resolver = $resolver;

        return $this;
    }

    /**
     * @param $field
     * @param $value
     *
     * @return $this
     */
    public function add($field, $value)
    {
        $this->context[$field] = $value;
        return $this;
    }

    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->context);
    }

    /**
     * @return mixed
     */
    public function resolve()
    {
        if (is_string($this->resolver) && class_exists($this->resolver)) {
            $className = $this->resolver;
            $resolver = new $className;
        } else {
            $resolver = $this->resolver;
        }

        return $resolver->resolve($this);
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize([$this->context, $this->resolver]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list($this->context, $this->resolver) = unserialize($serialized);
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->context[$name];
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->context[$name] = $value;
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->context[$name]);
    }


}
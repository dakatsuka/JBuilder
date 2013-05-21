<?php

namespace JBuilder;

/**
 * JSON Object
 *
 * @package JBuilder
 */
class JSON implements \JsonSerializable
{
    /**
     * @var array
     */
    private $data;

    /**
     * Initialize JSON Object
     *
     * @param null $data
     */
    public function __construct($data = null)
    {
        $this->data = $data;
    }

    /**
     * Set JSON element
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Magic method
     *
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments)
    {
        if (is_a($arguments[0], '\Closure')) {
            $this->data[$name] = $this->buildHash($arguments[0], false);
        }

        if (is_array($arguments[0]) || is_a($arguments[0], '\Traversable')) {
            $this->data[$name] = $this->buildArray($arguments[0], $arguments[1], false);
        }
    }

    /**
     * Build array
     *
     * @param $collection
     * @param callable $callback
     * @param bool $root
     * @throws \InvalidArgumentException
     * @return array|null
     */
    public function buildArray($collection, \Closure $callback, $root = true)
    {
        if (!is_array($collection) && !is_a($collection, '\Traversable')) {
            throw new \InvalidArgumentException();
        }

        if ($root) {
            $result =& $this->data;
        } else {
            $result = array();
        }

        foreach ($collection as $element) {
            $json = new JSON();
            $callback($json, $element);
            $result[] = $json;
        }

        return $result;
    }

    /**
     * @param callable $callback
     * @param bool $root
     * @return JSON
     */
    public function buildHash(\Closure $callback, $root = true)
    {
        if ($root) {
            $json = $this;
        } else {
            $json = new JSON();
        }

        $callback($json);

        return $json;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return $this->data;
    }
}

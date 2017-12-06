<?php

namespace TeamWorkPm\Response;

use IteratorAggregate;
use ArrayIterator;
use Countable;
use ArrayAccess;

abstract class Model implements IteratorAggregate, Countable, ArrayAccess
{
    /**
     * @var null
     */
    protected $string = null;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Model constructor.
     */
    final public function __construct()
    {
    }

    /**
     * @param $data
     * @param array $headers
     *
     * @return mixed
     */
    abstract public function parse($data, array $headers);

    /**
     * @param $filename
     *
     * @return bool|int
     */
    public function save($filename)
    {
        if (strpos($filename, '.') === false) {
            $class = get_called_class();
            $ext   = strtolower(substr($class, strrpos($class, '\\') + 1));
            $filename .= '.' . $ext;
        }
        $dirname = dirname($filename);
        // creamos el directorio en caso de que no exista
        if ($dirname && !is_dir($dirname)) {
            mkdir($dirname, 0777, true);
        }

        return file_put_contents($filename, $this->getContent());
    }

    /**
     * @return mixed
     */
    abstract protected function getContent();

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getContent();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /**
     * @param $name
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * @param $name
     */
    public function __unset($name)
    {
        unset($this->data[$name]);
    }
}

<?php
namespace Jimphle\DataStructure;

/**
 * I am an immutable map representation
 * use me like this:
 *   $map = new \Jimphle\DataStructure\Map(array("foo" => "bar"));
 *   $map->foo; #=> returns "bar"
 *   $map["foo"]; #=> returns "bar"
 *   $map["unknown"]; #=> fails with exception
 *   $map["foo"] = "buhu"; #=> fails with exception
 *   unset($map["foo"]); #=> fails with exception
 */
class Map extends Base
{
    /**
     * @throws \Jimphle\DataStructure\InvalidPropertyException
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->offsetExists($name);
    }

    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    public function __unset($name)
    {
        $this->offsetUnset($name);
    }

    /**
     * You should use this if you want to convert a n object into a map
     *
     * @param object $object
     * @return Map
     */
    public static function fromObject($object)
    {
        $payloadValue = get_object_vars($object);
        return static::fromArray($payloadValue);
    }

    /**
     * set a value in a nested map
     * @param array $keys
     * @param mixed $value
     * @return Map
     */
    public function setIn($keys, $value)
    {
        $lastKey = array_shift($keys);
        if (count($keys) > 0 && !isset($this->$lastKey->$keys[0])) {
            throw new \RuntimeException(sprintf("Key '%s->%s' does not exist.", $lastKey, $keys[0]));
        }
        if ($this->$lastKey instanceof Map) {
            return $this->merge(new self(array($lastKey => $this->$lastKey->setIn($keys, $value))));
        }
        return $this->merge(new self(array($lastKey => $value)));
    }
}

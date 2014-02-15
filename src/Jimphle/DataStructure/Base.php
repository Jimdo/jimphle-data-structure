<?php
namespace Jimphle\DataStructure;

abstract class Base implements BaseInterface
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->data = $data;
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        if ($this->payloadPropertyExists($offset)) {
            return $this->data[$offset];
        }

        throw new InvalidPropertyException('Missing key "' . $offset . '" in representation');
    }

    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException('no way, i am immutable');
    }

    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException('no way, i am immutable');
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array_map(
            function ($value) {
                if ($value instanceof BaseInterface) {
                    return $value->toArray();
                }
                return $value;
            },
            $this->data
        );
    }

    public function count()
    {
        return count($this->data);
    }

    /**
     * @param string $name
     * @return bool
     */
    private function payloadPropertyExists($name)
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * You should use this:
     *  * If you want to convert an associative array into a map:
     *      \Jimphle\DataStructure\Map::fromArray(array("foo" => "bar"));
     *  * If you want to convert a sequential indexed array into a vector:
     *      \Jimphle\DataStructure\Vector::fromArray(array("foo", "bar"));
     *
     * @param array $data
     * @return BaseInterface
     */
    public static function fromArray(array $data)
    {
        foreach ($data as $property => $value) {
            if ($value instanceof BaseInterface) {
                continue;
            }
            if (is_object($value)) {
                $data[$property] = Map::fromObject($value);
            }
            if (is_array($value)) {
                if (Vector::isSequentialList($value)) {
                    $data[$property] = Vector::fromArray($value);
                } else {
                    $data[$property] = Map::fromArray($value);
                }
            }
        }
        return new static($data);
    }

    /**
     * @param BaseInterface $other
     * @return BaseInterface
     */
    public function merge(BaseInterface $other)
    {
        return new static(array_merge($this->getPayload(), $other->getPayload()));
    }

    public function getPayload()
    {
        return $this->data;
    }
}

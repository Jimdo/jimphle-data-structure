<?php
namespace Jimphle\DataStructure;

/**
 * I am an immutable sequential list representation
 * use me like this:
 *   $map = new \Jimphle\DataStructure\Vector(array("foo", "bar"));
 *   $map[0]; #=> returns "bar"
 *   $map[1]; #=> returns "bar"
 *   $map[2]; #=> fails with exception
 *   $map[1] = "buhu"; #=> fails with exception
 *   unset($map[1]); #=> fails with exception
 */
class Vector extends Base implements BaseInterface
{
    public function offsetGet($offset)
    {
        $this->assertOffsetIsInt($offset);
        return parent::offsetGet($offset);
    }

    public function offsetExists($offset)
    {
        $this->assertOffsetIsInt($offset);
        return parent::offsetExists($offset);
    }

    /**
     * check if array is a sequential list
     * ATTENTION: needs a copy of the whole array for the check
     *
     * @param array $array
     * @return bool
     */
    public static function isSequentialList(array $array)
    {
        return array_values($array) === $array;
    }

    private function assertOffsetIsInt($offset)
    {
        if (!is_int($offset)) {
            throw new  \InvalidArgumentException(sprintf("Passed offset %s has to be an integer.", $offset));
        }
    }

    public function __get($name)
    {
        throw new \BadMethodCallException('i have no public properties');
    }

    public function __isset($name)
    {
        throw new \BadMethodCallException('i have no public properties');
    }
}

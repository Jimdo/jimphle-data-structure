<?php
namespace Jimphle\DataStructure;

interface BaseInterface extends \ArrayAccess, \IteratorAggregate, \Countable
{
    /**
     * @return string
     */
    public function toJson();

    /**
     * @return array
     */
    public function toArray();

    /**
     * @return string
     */
    public function __toString();

    /**
     * @return array
     */
    public function getPayload();

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name);

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name);

    /**
     * @param BaseInterface $other
     * @return BaseInterface
     */
    public function merge(BaseInterface $other);
}

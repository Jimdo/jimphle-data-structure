<?php
namespace Jimphle\DataStructure;

class NullObject extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __get($name)
    {
    }

    public function __isset($name)
    {
    }
}

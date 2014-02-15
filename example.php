<?php
require __DIR__ . '/vendor/autoload.php';

$map = new \Jimphle\DataStructure\Map(
    array(
        'foo' => 'bar'
    )
);
echo $map->foo;

$map = new \Jimphle\DataStructure\Map(
    array(
        'foo-1' => 'bar'
    )
);
echo $map['foo-1'];

$map = new \Jimphle\DataStructure\Map(
    array(
        'who?' => new \Jimphle\DataStructure\Vector(
            array(
                new Jimphle\DataStructure\Map(
                    array(
                        'foo' => 'bar'
                    )
                )
            )
        )
    )
);
echo $map->toJson();


$vector = new \Jimphle\DataStructure\Vector(
    array(
        'foo',
        'bar'
    )
);

echo $vector[1];
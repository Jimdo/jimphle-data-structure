# Jimphle-data-structure

Jimdo PHP library extraction of data-structure component.

This comes with a Map and a Vector and a Null implementation of the BaseInterface.
Facts:
 * Immutable
 * Throws InvalidPropertyException on none-existing keys
 * Is able convert complete trees of different data structures to json
 * Is sometimes not very efficient.
   For example the fromArray method uses the Vector::isSequentialList check which copies the complete array in memory

A Vector is a representation of an array with sequential numeric indexes:
```php
$vector = new \Jimphle\DataStructure\Vector(
    array(
        'foo',
        'bar'
    )
);

echo $vector[1];
```

A Map is a representation of an array with key and value:
```php
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
```

Convert an object tree to json:
```php
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
```

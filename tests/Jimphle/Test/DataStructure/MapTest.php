<?php
namespace Jimphle\Test\DataStructure;

use Jimphle\DataStructure\Map;

class MapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldReturnTheArrayRepresentation()
    {
        $value = array('foo' => 'bar');
        $payload = new Map(
            $value
        );

        $this->assertEquals($value, $payload->toArray());
    }

    /**
     * @test
     */
    public function toArrayShouldWorkRecursively()
    {
        $value = array('foo' => new Map(array('bar' => 'baz')));
                $payload = new Map(
                    $value
                );

        $this->assertEquals(array('foo' => array('bar' => 'baz')), $payload->toArray());
    }

    /**
     * @test
     */
    public function itShouldReturnTheObjectRepresentation()
    {
        $value = 'blub';

        $payload = new Map(
            array('foo' => new Map(array('bar' => $value)))
        );

        $this->assertEquals($payload->foo->bar, 'blub');
    }

    /**
     * @test
     */
    public function itShouldBeCountable()
    {
        $payload = new Map(
            array('foo' => new Map(array('bar' => 'blub')), 'huhu' => 'haha')
        );
        $this->assertThat(count($payload), $this->equalTo(2));
    }

    /**
     * @test
     */
    public function itShouldReturnTheJsonRepresentation()
    {
        $value = array('foo' => 'bar');

        $string = json_encode($value);

        $payload = new Map(
            $value
        );

        $this->assertEquals($string, $payload->toJson());
        $this->assertEquals($string, (string)$payload);
    }

    /**
     * @test
     */
    public function itShouldReturnTrueIfThePropertyExists()
    {
        $value = array('foo' => 'bar');
        $payload = new Map(
            $value
        );

        $this->assertTrue(isset($payload->foo));
    }

    /**
     * @test
     */
    public function itShouldSupportCamelCaseObjectProperties()
    {
        $object = new \stdClass();
        $object->bar = 'baz';

        $value = array('fooBar' => $object);
        $payload = new Map(
            $value
        );

        $this->assertTrue(isset($payload->fooBar));
    }

    /**
     * @test
     * @expectedException Jimphle\DataStructure\InvalidPropertyException
     */
    public function itShouldThrowAnExceptionIfPropertyDoesNotExist()
    {
        $value = array('foo' => 'bar');
        $payload = new Map(
            $value
        );

        $payload->bar;
    }

    /**
     * @test
     */
    public function itShouldBehaveLikeAnArray()
    {
        $value = array('foo' => new Map(array('bar' => 'baz')));
        $payload = new Map(
            $value
        );
        $this->assertThat(isset($payload['foo']['bar']), $this->isTrue());
        $this->assertThat($payload['foo']['bar'], $this->equalTo('baz'));
    }

    /**
     * @test
     */
    public function itShouldBeIterableAsAList()
    {
        $list = new Map(array('foo', 'bar'));
        $rows = array();
        foreach ($list as $key => $row) {
            $rows[$key] = $row;
        }
        $this->assertThat($rows, $this->equalTo(array('foo', 'bar')));
    }

    /**
     * @test
     */
    public function itShouldBeIterableAsAMap()
    {
        $list = new Map(array('foo' => 'bar'));
        $rows = array();
        foreach ($list as $key => $row) {
            $rows[$key] = $row;
        }
        $this->assertThat($rows, $this->equalTo(array('foo' => 'bar')));
    }

    /**
     * @test
     */
    public function fromObjectShouldCreateRepresentationFromObject()
    {
        $object = new \stdClass;
        $object->foo = 'blaaa';
        $representation = Map::fromObject($object);
        $this->assertThat($representation->foo, $this->equalTo('blaaa'));
    }

    /**
     * @test
     */
    public function fromObjectShouldSupportNestedObjects()
    {
        $foo = new \stdClass;
        $foo->bar = 'blaaa';
        $object = new \stdClass;
        $object->foo = $foo;
        $representation = Map::fromObject($object);
        $this->assertThat($representation->foo, $this->equalTo(new Map(array('bar' => 'blaaa'))));
    }

    /**
     * @test
     */
    public function fromObjectShouldSupportNestedArrays()
    {
        $expected = 'baz';

        $object = new \stdClass;
        $object->foo = array('bar' => 'baz');
        $payload = Map::fromObject($object);

        $this->assertEquals($expected, $payload->foo['bar']);
    }

    /**
     * @test
     */
    public function fromArrayShouldSupportNestedObjects()
    {
        $expected = 'baz';

        $value = array('foo' => new Map(array('bar' => 'baz')));
        $payload = Map::fromArray(
            $value
        );

        $this->assertEquals($expected, $payload->foo->bar);
    }

    /**
     * @test
     */
    public function fromArrayShouldSupportNestedArrays()
    {
        $expected = 'baz';

        $payload = Map::fromArray(array('foo' => array('bar' => 'baz')));

        $this->assertInstanceOf('\Jimphle\DataStructure\Map', $payload->foo);
        $this->assertEquals($expected, $payload->foo['bar']);
    }

    /**
     * @test
     */
    public function fromArrayShouldConvertSequentialArraysToVector()
    {
        $expected = 'baz';

        $payload = Map::fromArray(array('foo' => array(array('bar' => 'baz'))));

        $this->assertInstanceOf('\Jimphle\DataStructure\Vector', $payload->foo);
        $this->assertEquals($expected, $payload->foo[0]['bar']);
    }

    /**
     * @test
     */
    public function setShouldSetAValueInAMap()
    {
        /**
         * @var Map $payload
         */
        $payload = Map::fromArray(array('foo' => 'bar'));

        $payload = $payload->set('foo', 'boo');
        $this->assertThat($payload->foo, $this->equalTo('boo'));
    }

    /**
     * @test
     */
    public function setInShouldReturnTheSameMapWhenThereIsNoNesting()
    {
        /**
         * @var Map $payload
         */
        $payload = Map::fromArray(array('foo' => 'baz'));
        $payload = $payload->setIn(array('foo'), 'boo');
        $this->assertThat($payload->foo, $this->equalTo('boo'));
    }

    /**
     * @test
     */
    public function setInShouldSetAValueInANestedMap()
    {
        /**
         * @var Map $payload
         */
        $payload = Map::fromArray(array('foo' => array('bar' => 'baz', 'boot' => array('spam' => 'eggs'))));

        $payload = $payload->setIn(array('foo', 'bar'), 'boo');
        $this->assertThat($payload->foo->bar, $this->equalTo('boo'));

        $payload = $payload->setIn(array('foo', 'boot', 'spam'), 'ham');
        $this->assertThat($payload->foo->boot->spam, $this->equalTo('ham'));

        $payload = new Map(array('foo' => new Map()));
        $payload = $payload->setIn(array('foo'), 'boo');
        $this->assertThat($payload->foo, $this->equalTo('boo'));
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function setInShouldFailWhenKeyDoesNotExist()
    {
        /**
         * @var Map $payload
         */
        $payload = Map::fromArray(array('foo' => 'bar'));

        var_dump($payload->setIn(array('foo', 'bar'), 'boo'));
    }
}

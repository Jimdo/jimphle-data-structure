<?php
namespace Jimphle\Test\DataStructure;

use Jimphle\DataStructure\InvalidPropertyException;
use Jimphle\DataStructure\Vector;
use PHPUnit\Framework\TestCase;

class VectorTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldThrowAnExceptionIfGetOffsetIsNotInt()
    {
        $this->expectException(\InvalidArgumentException::class);
        $payload = new Vector(array("foo"));
        $payload->offsetGet("huhu");
    }

    /**
     * @test
     */
    public function itShouldThrowAnExceptionIfIssetOffsetIsNotInt()
    {
        $this->expectException(\InvalidArgumentException::class);
        $payload = new Vector(array("foo"));
        $payload->offsetExists("huhu");
    }

    /**
     * @test
     */
    public function itShouldReturnTheArrayRepresentation()
    {
        $value = array('foo', 'bar');
        $payload = new Vector(
            $value
        );

        $this->assertEquals($value, $payload->toArray());
    }

    /**
     * @test
     */
    public function toArrayShouldWorkRecursively()
    {
        $value = array(new Vector(array('bar', 'baz')));
        $payload = new Vector(
            $value
        );

        $this->assertEquals(array(array('bar', 'baz')), $payload->toArray());
    }

    /**
     * @test
     */
    public function itShouldBeUsableAsVector()
    {
        $value = 'blub';

        $payload = new Vector(
            array(new Vector(array('bar', $value)))
        );

        $this->assertEquals($payload[0][1], $value);
    }

    /**
     * @test
     */
    public function itShouldBeCountable()
    {
        $payload = new Vector(
            array(new Vector(array('bar' => 'blub')), 'haha')
        );
        $this->assertThat(count($payload), $this->equalTo(2));
    }

    /**
     * @test
     */
    public function itShouldReturnTheJsonRepresentation()
    {
        $value = array('foo', 'bar');

        $string = json_encode($value);

        $payload = new Vector(
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
        $value = array('foo', 'bar');
        $payload = new Vector(
            $value
        );

        $this->assertTrue(isset($payload[0]));
    }

    /**
     * @test
     */
    public function itShouldThrowAnExceptionIfPropertyDoesNotExist()
    {
        $this->expectException(InvalidPropertyException::class);
        $value = array('foo', 'bar');
        $payload = new Vector(
            $value
        );

        $payload[2];
    }

    /**
     * @test
     */
    public function itShouldBeIterableAsAList()
    {
        $list = new Vector(array('foo', 'bar'));
        $rows = array();
        foreach ($list as $key => $row) {
            $rows[$key] = $row;
        }
        $this->assertThat($rows, $this->equalTo(array('foo', 'bar')));
    }

    /**
     * @test
     */
    public function fromArrayShouldSupportNestedArrays()
    {
        $expected = 'bar';

        $payload = Vector::fromArray(array(array('foo', 'bar')));

        $this->assertInstanceOf('\Jimphle\DataStructure\Vector', $payload[0]);
        $this->assertEquals($expected, $payload[0][1]);
    }
}

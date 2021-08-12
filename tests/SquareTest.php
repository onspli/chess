<?php
namespace Onspli\Chess;
use PHPUnit\Framework\TestCase;

/**
 * @covers Onspli\Chess\Square
 */
final class SquareTest extends TestCase
{

  public function testInitialization() : void
  {
    $s = new Square('-');
    $this->assertTrue($s->is_null());

    $s = new Square('e4');
    $this->assertFalse($s->is_null());
    $this->assertEquals('e4', $s->alg());
    $this->assertEquals(4, $s->file());
    $this->assertEquals(3, $s->rank());

    $s = new Square(4, 3);
    $this->assertFalse($s->is_null());
    $this->assertEquals('e4', $s->alg());
    $this->assertEquals(4, $s->file());
    $this->assertEquals(3, $s->rank());
  }


}

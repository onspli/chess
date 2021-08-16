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

    $s = new Square;
    $this->assertTrue($s->is_null());

    $s = new Square('e4');
    $this->assertFalse($s->is_null());
    $this->assertEquals('e4', $s->san());
    $this->assertEquals(4, $s->file_index());
    $this->assertEquals(3, $s->rank_index());

    $s = new Square(4, 3);
    $this->assertFalse($s->is_null());
    $this->assertEquals('e4', $s->san());
    $this->assertEquals(4, $s->file_index());
    $this->assertEquals(3, $s->rank_index());

    $s = new Square(-1, 0);
    $this->assertTrue($s->is_null());

    $s = new Square(8, 0);
    $this->assertTrue($s->is_null());

    $s = new Square(0, 8);
    $this->assertTrue($s->is_null());

    $s = new Square(0, -1);
    $this->assertTrue($s->is_null());

    $s = new Square(0.5, 0);
    $this->assertTrue($s->is_null());

  }

  public function testRelativeSquares() : void
  {

    $s = new Square('e4');
    $this->assertEquals('e4', $s->relative(0, 0)->san());
    $this->assertEquals('h4', $s->relative(3, 0)->san());
    $this->assertEquals('-', $s->relative(4, 0)->san());
    $this->assertEquals('e8', $s->relative(0, 4)->san());
    $this->assertEquals('-', $s->relative(0, 5)->san());
    $this->assertEquals('a4', $s->relative(-4, 0)->san());
    $this->assertEquals('-', $s->relative(-5, 0)->san());
    $this->assertEquals('e1', $s->relative(0, -3)->san());
    $this->assertEquals('-', $s->relative(0, -4)->san());
  }

  public function testInvalid1() : void
  {
    $this->expectException(ParseException::class);
    new Square('x');
  }

  public function testInvalid2() : void
  {
    $this->expectException(ParseException::class);
    new Square('abc');
  }

  public function testInvalid3() : void
  {
    $this->expectException(ParseException::class);
    new Square('i1');
  }

  public function testInvalid4() : void
  {
    $this->expectException(ParseException::class);
    new Square('a0');
  }

  public function testInvalid5() : void
  {
    $this->expectException(ParseException::class);
    new Square('a9');
  }

  public function testNullRank() : void
  {
    $this->expectException(\OutOfBoundsException::class);
    $s = new Square('-');
    $s->rank_index();
  }

  public function testNullFile() : void
  {
    $this->expectException(\OutOfBoundsException::class);
    $s = new Square('-');
    $s->file_index();
  }

  public function testAddToArrayAsObjects() : void
  {
    $arr = [];
    $s = new Square('-');
    $s->push_to_array($arr, true);
    $this->assertEqualsCanonicalizing([], $arr);

    $s = new Square('e1');
    $s->push_to_array($arr, true);
    $this->assertEqualsCanonicalizing([new Square('e1')], $arr);

    $s = new Square('g1');
    $s->push_to_array($arr, true);
    $this->assertEqualsCanonicalizing([new Square('e1'), new Square('g1')], $arr);
  }

  public function testAddToArray() : void
  {
    $arr = [];
    $s = new Square('-');
    $s->push_to_array($arr);
    $this->assertEqualsCanonicalizing([], $arr);

    $s = new Square('e1');
    $s->push_to_array($arr);
    $this->assertEqualsCanonicalizing(['e1'], $arr);

    $s = new Square('g1');
    $s->push_to_array($arr);
    $this->assertEqualsCanonicalizing(['e1', 'g1'], $arr);
  }


}

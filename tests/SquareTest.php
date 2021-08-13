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
    $this->assertEquals('e4', $s->alg());
    $this->assertEquals(4, $s->file());
    $this->assertEquals(3, $s->rank());

    $s = new Square(4, 3);
    $this->assertFalse($s->is_null());
    $this->assertEquals('e4', $s->alg());
    $this->assertEquals(4, $s->file());
    $this->assertEquals(3, $s->rank());

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
    $this->assertEquals('e4', $s->rel(0, 0)->alg());
    $this->assertEquals('h4', $s->rel(3, 0)->alg());
    $this->assertEquals('-', $s->rel(4, 0)->alg());
    $this->assertEquals('e8', $s->rel(0, 4)->alg());
    $this->assertEquals('-', $s->rel(0, 5)->alg());
    $this->assertEquals('a4', $s->rel(-4, 0)->alg());
    $this->assertEquals('-', $s->rel(-5, 0)->alg());
    $this->assertEquals('e1', $s->rel(0, -3)->alg());
    $this->assertEquals('-', $s->rel(0, -4)->alg());
    $this->assertEquals('e5', $s->n()->alg());
    $this->assertEquals('e3', $s->s()->alg());
    $this->assertEquals('d4', $s->w()->alg());
    $this->assertEquals('f4', $s->e()->alg());
    $this->assertEquals('d5', $s->nw()->alg());
    $this->assertEquals('f5', $s->ne()->alg());
    $this->assertEquals('d3', $s->sw()->alg());
    $this->assertEquals('f3', $s->se()->alg());
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
    $s->rank();
  }

  public function testNullFile() : void
  {
    $this->expectException(\OutOfBoundsException::class);
    $s = new Square('-');
    $s->file();
  }

  public function testAddToArrayAsObjects() : void
  {
    $arr = [];
    $s = new Square('-');
    $s->add_to($arr, true);
    $this->assertEqualsCanonicalizing([], $arr);

    $s = new Square('e1');
    $s->add_to($arr, true);
    $this->assertEqualsCanonicalizing([new Square('e1')], $arr);

    $s = new Square('g1');
    $s->add_to($arr, true);
    $this->assertEqualsCanonicalizing([new Square('e1'), new Square('g1')], $arr);
  }

  public function testAddToArray() : void
  {
    $arr = [];
    $s = new Square('-');
    $s->add_to($arr);
    $this->assertEqualsCanonicalizing([], $arr);

    $s = new Square('e1');
    $s->add_to($arr);
    $this->assertEqualsCanonicalizing(['e1'], $arr);

    $s = new Square('g1');
    $s->add_to($arr);
    $this->assertEqualsCanonicalizing(['e1', 'g1'], $arr);
  }


}

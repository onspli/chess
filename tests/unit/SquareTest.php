<?php
namespace Onspli\Chess;
use PHPUnit\Framework\TestCase;

/**
 * @covers Onspli\Chess\Square
 */
final class SquareTest extends TestCase
{

  public function testInitializationRegular() : void
  {
    $s = new Square('e4');
    $this->assertTrue($s->is_regular());

    $s = new Square(4, 3);
    $this->assertTrue($s->is_regular());
  }

  public function testInitializationNull() : void
  {
    $s = new Square;
    $this->assertTrue($s->is_null());

    $s = new Square('-');
    $this->assertTrue($s->is_null());

    $s = new Square('');
    $this->assertTrue($s->is_null());

    $s = new Square(null, null);
    $this->assertTrue($s->is_null());
  }

  public function testStringable() : void
  {
    $s = new Square('e4');
    $this->assertEquals('e4', (string) $s);
  }

  public function testInitializationFile() : void
  {
    $s = new Square('e');
    $this->assertTrue($s->is_file());
    $this->assertEquals('e', $s->get_file());
    $this->assertEquals('', $s->get_rank());

    $s = new Square(4, null);
    $this->assertTrue($s->is_file());
  }

  public function testInitializationRank() : void
  {

    $s = new Square('4');
    $this->assertTrue($s->is_rank());
    $this->assertEquals('', $s->get_file());
    $this->assertEquals('4', $s->get_rank());

    $s = new Square(null, 3);
    $this->assertTrue($s->is_rank());
  }

  public function testRelativeSquares() : void
  {
    $s = new Square('e4');
    $this->assertEquals('e4', $s->get_relative_square(0, 0)->export());
    $this->assertEquals('h4', $s->get_relative_square(3, 0)->export());
    $this->assertEquals('-', $s->get_relative_square(4, 0)->export());
    $this->assertEquals('e8', $s->get_relative_square(0, 4)->export());
    $this->assertEquals('-', $s->get_relative_square(0, 5)->export());
    $this->assertEquals('a4', $s->get_relative_square(-4, 0)->export());
    $this->assertEquals('-', $s->get_relative_square(-5, 0)->export());
    $this->assertEquals('e1', $s->get_relative_square(0, -3)->export());
    $this->assertEquals('-', $s->get_relative_square(0, -4)->export());
  }

  public function testRelativeOnNullThrows() : void
  {
    $s = new Square;
    $this->expectException(\OutOfBoundsException::class);
    $s->get_relative_square(1, 0);
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

  public function testInvalidFloatFile() : void
  {
    $this->expectException(ParseException::class);
    $s = new Square(0.5, 0);
  }

  public function testInvalidFloarRank() : void
  {
    $this->expectException(ParseException::class);
    $s = new Square(0, 0.5);
  }

  public function testNullRank() : void
  {
    $s = new Square('-');
    $this->expectException(\OutOfBoundsException::class);
    $s->get_rank();
  }

  public function testNullRankIndex() : void
  {
    $s = new Square('-');
    $this->expectException(\OutOfBoundsException::class);
    $s->get_rank_index();
  }

  public function testNullFile() : void
  {
    $s = new Square('-');
    $this->expectException(\OutOfBoundsException::class);
    $s->get_file();
  }

  public function testNullFileIndex() : void
  {
    $s = new Square('-');
    $this->expectException(\OutOfBoundsException::class);
    $s->get_file_index();
  }

  public function testFileIndexRank() : void
  {
    $this->expectException(\OutOfBoundsException::class);
    $s = new Square('4');
    $s->get_file_index();
  }

  public function testRankIndexFile() : void
  {
    $s = new Square('e');
    $this->expectException(\OutOfBoundsException::class);
    $s->get_rank_index();
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

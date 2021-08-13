<?php
namespace Onspli\Chess;
use PHPUnit\Framework\TestCase;

/**
 * @covers Onspli\Chess\Board
 */
final class BoardTest extends TestCase
{

  public function testInitializationAndExport() : void
  {
    $board = new Board;
    $this->assertEquals('', $board->square('e4'));
    $board->set_square('e4', 'P');
    $this->assertEquals('P', $board->square('e4'));

    $board = new Board;
    $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR', $board->export());

    $board = new Board('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR');
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR', $board->export());
  }

  public function testNothrow() : void
  {
    $board = new Board;
    $this->assertNull($board->set_square_nothrow('xx', 'p'));
    $this->assertEquals('', $board->square_nothrow('xx'));
  }


  public function testFind() : void
  {
    $board = new Board('8/8/8/8/8/8/8/8');
    $board->set_square('e5', 'N');
    $board->set_square('a2', 'P');
    $board->set_square('e4', 'P');

    $res = $board->find('P');
    $this->assertEqualsCanonicalizing(['a2', 'e4'], $res);

    $res = $board->find('N');
    $this->assertEqualsCanonicalizing(['e5'], $res);

    $res = $board->find('Q');
    $this->assertEquals([], $res);

    $res = $board->find('');
    $this->assertEquals(61, sizeof($res));
  }

  public function testAttackedSquaresByPawns() : void
  {
    $board = new Board;
    $res = $board->attacked_squares('e4', 'P');
    $this->assertEqualsCanonicalizing(['f5', 'd5'], $res);
    $res = $board->attacked_squares('e5', 'p');
    $this->assertEqualsCanonicalizing(['d4', 'f4'], $res);

    $res = $board->attacked_squares('a2', 'P');
    $this->assertEqualsCanonicalizing(['b3'], $res);

    $res = $board->attacked_squares('h2', 'P');
    $this->assertEqualsCanonicalizing(['g3'], $res);

    $res = $board->attacked_squares('a2', 'p');
    $this->assertEqualsCanonicalizing(['b1'], $res);

    $res = $board->attacked_squares('h2', 'p');
    $this->assertEqualsCanonicalizing(['g1'], $res);
  }

  public function testAttackedSquaresByKings() : void
  {
    $board = new Board;
    $ref = ['a1', 'b1', 'c1', 'c2', 'c3', 'b3', 'a3', 'a2'];
    $res = $board->attacked_squares('b2', 'k');
    $this->assertEqualsCanonicalizing($ref, $res);

    $res = $board->attacked_squares('b2', 'K');
    $this->assertEqualsCanonicalizing($ref, $res);

    $ref = ['a2', 'b1', 'b2'];
    $res = $board->attacked_squares('a1', 'k');
    $this->assertEqualsCanonicalizing($ref, $res);
  }

  public function testAttackedSquaresByKnights() : void
  {
    $board = new Board;
    $ref = ['d2', 'c3', 'c5', 'd6', 'f6', 'g5', 'g3', 'f2'];
    $res = $board->attacked_squares('e4', 'n');
    $this->assertEqualsCanonicalizing($ref, $res);

    $res = $board->attacked_squares('e4', 'N');
    $this->assertEqualsCanonicalizing($ref, $res);
  }

  public function testAttackedSquaresByBishops() : void
  {
    $board = new Board;
    $ref = ['f5', 'g6', 'h7', 'd3', 'c2', 'f3', 'g2', 'd5', 'c6', 'b7'];
    $res = $board->attacked_squares('e4', 'b');
    $this->assertEqualsCanonicalizing($ref, $res);

    $res = $board->attacked_squares('e4', 'B');
    $this->assertEqualsCanonicalizing($ref, $res);
  }

  public function testAttackedSquaresByRooks() : void
  {
    $board = new Board;
    $ref = ['e5', 'e6', 'e7', 'e3', 'e2', 'f4', 'g4', 'h4', 'd4', 'c4', 'b4', 'a4'];
    $res = $board->attacked_squares('e4', 'r');
    $this->assertEqualsCanonicalizing($ref, $res);

    $res = $board->attacked_squares('e4', 'R');
    $this->assertEqualsCanonicalizing($ref, $res);
  }

  public function testAttackedSquaresByQueens() : void
  {
    $board = new Board;
    $ref = ['f5', 'g6', 'h7', 'd3', 'c2', 'f3', 'g2', 'd5', 'c6', 'b7', 'e5', 'e6', 'e7', 'e3', 'e2', 'f4', 'g4', 'h4', 'd4', 'c4', 'b4', 'a4'];
    $res = $board->attacked_squares('e4', 'q');
    $this->assertEqualsCanonicalizing($ref, $res);

    $res = $board->attacked_squares('e4', 'Q');
    $this->assertEqualsCanonicalizing($ref, $res);
  }

  public function testParseException1() : void
  {
    $this->expectException(ParseException::class);
    new Board('9/8/8/8/8/8/8/8');
  }

  public function testParseException2() : void
  {
    $this->expectException(ParseException::class);
    new Board('8/8/8/8/8/8/8/8/8');
  }

  public function testValidatePiece() : void
  {
    $this->expectException(ParseException::class);
    $board = new Board();
    $board->set_square('e4', 'x');
  }

  public function testValidateSquare() : void
  {
    $this->expectException(\OutOfBoundsException::class);
    $board = new Board();
    $board->set_square('-', 'p');
  }

  public function testPiecesOnSquares() : void
  {
    $board = new Board;
    $ref = ['R', 'N', 'N', 'r', 'r', 'K', 'P', 'P'];
    $res = $board->pieces_on_squares(['a1', 'e4', 'b1', 'g1', 'a8', 'h8', 'e1', 'b2', 'c2']);
    $this->assertEqualsCanonicalizing($ref, $res);
  }

  public function testCopy() : void
  {
    $board1 = new Board('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR');
    $board2 = $board1->copy();

    $this->assertEquals($board1->export(), $board2->export());
    $board2->set_square('a1', '');
    $this->assertNotEquals($board1->export(), $board2->export());
  }

  public function testInvalidColor() : void
  {
    $board = new Board();
    $this->expectException(ParseException::class);
    $board->is_check('x');
  }

  public function testCheckDetection() : void
  {
    $board = new Board;
    $this->assertFalse($board->is_check('w'));
    $this->assertFalse($board->is_check('b'));

    $board = new Board('8/8/8/8/8/8/3P4/4K3');
    $this->assertFalse($board->is_check('w'));
    $board = new Board('8/8/8/8/8/8/3p4/4K3');
    $this->assertTrue($board->is_check('w'));

    $board = new Board('8/8/8/8/8/8/2N5/4K3');
    $this->assertFalse($board->is_check('w'));
    $board = new Board('8/8/8/8/8/8/2n5/4K3');
    $this->assertTrue($board->is_check('w'));

    $board = new Board('4R3/8/8/8/8/8/8/4K3');
    $this->assertFalse($board->is_check('w'));
    $board = new Board('4r3/8/8/8/8/8/8/4K3');
    $this->assertTrue($board->is_check('w'));

    $board = new Board('8/8/8/B7/8/8/8/4K3');
    $this->assertFalse($board->is_check('w'));
    $board = new Board('8/8/8/b7/8/8/8/4K3');
    $this->assertTrue($board->is_check('w'));

    $board = new Board('8/8/8/8/7Q/8/8/4K3');
    $this->assertFalse($board->is_check('w'));
    $board = new Board('8/8/8/8/7q/8/8/4K3');
    $this->assertTrue($board->is_check('w'));

    $board = new Board('8/8/8/8/7q/8/5n2/4K3');
    $this->assertFalse($board->is_check('w'));
  }

  public function testCheckTwoKings1() : void
  {
    $this->expectException(RulesException::class);
    $board = new Board('8/8/8/8/8/8/8/KK4kk');
    $board->is_check('w');
  }

  public function testCheckTwoKings2() : void
  {
    $this->expectException(RulesException::class);
    $board = new Board('8/8/8/8/8/8/8/KK4kk');
    $board->is_check('w');
  }

  public function testCheckAdjacentKings() : void
  {
    $this->expectException(RulesException::class);
    $board = new Board('8/8/8/8/8/8/8/Kk6');
    $board->is_check('w');
  }

  public function testPreviewDoesNotThrow() : void
  {
    $board = new Board;
    $board->preview();
    $this->assertNull(null);

  }

}

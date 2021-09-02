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
    $this->assertEquals('', $board->get_square('e4'));
    $board->set_square('e4', 'P');
    $this->assertEquals('P', $board->get_square('e4'));

    $board = new Board;
    $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR', $board->export());

    $board = new Board;
    $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR', (string) $board);

    $board = new Board('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR');
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR', $board->export());
  }

  /*
  public function testFindSquaresWithPiece() : void
  {
    $board = new Board('8/8/8/8/8/8/8/8');
    $board->set_square('e5', 'N');
    $board->set_square('a2', 'P');
    $board->set_square('e4', 'P');

    $res = $board->find_squares_with_piece('P');
    $this->assertEqualsCanonicalizing(['a2', 'e4'], $res);

    $res = $board->find_squares_with_piece('N');
    $this->assertEqualsCanonicalizing(['e5'], $res);

    $res = $board->find_squares_with_piece('Q');
    $this->assertEquals([], $res);

    $res = $board->find_squares_with_piece('');
    $this->assertEquals(61, sizeof($res));
  }
  */

  public function testDedendedSquaresByPawns() : void
  {
    $board = new Board;
    $res = $board->get_defended_squares('e4', 'P');
    $this->assertEqualsCanonicalizing(['f5', 'd5'], $res);
    $res = $board->get_defended_squares('e5', 'p');
    $this->assertEqualsCanonicalizing(['d4', 'f4'], $res);

    $res = $board->get_defended_squares('a2', 'P');
    $this->assertEqualsCanonicalizing(['b3'], $res);

    $res = $board->get_defended_squares('h2', 'P');
    $this->assertEqualsCanonicalizing(['g3'], $res);

    $res = $board->get_defended_squares('a2', 'p');
    $this->assertEqualsCanonicalizing(['b1'], $res);

    $res = $board->get_defended_squares('h2', 'p');
    $this->assertEqualsCanonicalizing(['g1'], $res);
  }

  public function testDedendedSquaresByKings() : void
  {
    $board = new Board;
    $ref = ['a1', 'b1', 'c1', 'c2', 'c3', 'b3', 'a3', 'a2'];
    $res = $board->get_defended_squares('b2', 'k');
    $this->assertEqualsCanonicalizing($ref, $res);

    $res = $board->get_defended_squares('b2', 'K');
    $this->assertEqualsCanonicalizing($ref, $res);

    $ref = ['a2', 'b1', 'b2'];
    $res = $board->get_defended_squares('a1', 'k');
    $this->assertEqualsCanonicalizing($ref, $res);
  }

  public function testDedendedSquaresByKnights() : void
  {
    $board = new Board;
    $ref = ['d2', 'c3', 'c5', 'd6', 'f6', 'g5', 'g3', 'f2'];
    $res = $board->get_defended_squares('e4', 'n');
    $this->assertEqualsCanonicalizing($ref, $res);

    $res = $board->get_defended_squares('e4', 'N');
    $this->assertEqualsCanonicalizing($ref, $res);
  }

  public function testDedendedSquaresByBishops() : void
  {
    $board = new Board;
    $ref = ['f5', 'g6', 'h7', 'd3', 'c2', 'f3', 'g2', 'd5', 'c6', 'b7'];
    $res = $board->get_defended_squares('e4', 'b');
    $this->assertEqualsCanonicalizing($ref, $res);

    $res = $board->get_defended_squares('e4', 'B');
    $this->assertEqualsCanonicalizing($ref, $res);
  }

  public function testDedendedSquaresByRooks() : void
  {
    $board = new Board;
    $ref = ['e5', 'e6', 'e7', 'e3', 'e2', 'f4', 'g4', 'h4', 'd4', 'c4', 'b4', 'a4'];
    $res = $board->get_defended_squares('e4', 'r');
    $this->assertEqualsCanonicalizing($ref, $res);

    $res = $board->get_defended_squares('e4', 'R');
    $this->assertEqualsCanonicalizing($ref, $res);
  }

  public function testDedendedSquaresByQueens() : void
  {
    $board = new Board;
    $ref = ['f5', 'g6', 'h7', 'd3', 'c2', 'f3', 'g2', 'd5', 'c6', 'b7', 'e5', 'e6', 'e7', 'e3', 'e2', 'f4', 'g4', 'h4', 'd4', 'c4', 'b4', 'a4'];
    $res = $board->get_defended_squares('e4', 'q');
    $this->assertEqualsCanonicalizing($ref, $res);

    $res = $board->get_defended_squares('e4', 'Q');
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

  /*
  public function testPiecesOnSquares() : void
  {
    $board = new Board;
    $ref = ['R', 'N', 'N', 'r', 'r', 'K', 'P', 'P'];
    $res = $board->get_pieces_on_squares(['a1', 'e4', 'b1', 'g1', 'a8', 'h8', 'e1', 'b2', 'c2']);
    $this->assertEqualsCanonicalizing($ref, $res);
  }
  */

  public function testIsSquareVacant() : void
  {
    $board = new Board;
    $this->assertTrue($board->is_square_vacant('e4'));
    $this->assertFalse($board->is_square_vacant('e1'));
  }

  public function testIsPieceOnSquare() : void
  {
    $board = new Board;
    $this->assertTrue($board->is_piece_on_square('K', 'e1'));
    $this->assertFalse($board->is_piece_on_square('k', 'e1'));
    $this->assertFalse($board->is_piece_on_square('Q', 'e1'));
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
    $board->is_check('b');
  }

  public function testSquareAttackedByKing() : void
  {
    $board = new Board('8/8/8/8/8/8/8/4K3');
    $this->assertTrue($board->is_square_attacked('d1', 'w'));
  }

  public function testPreviewDoesNotThrow() : void
  {
    $board = new Board;
    $board->preview();
    $this->assertNull(null);

  }

  public function testReachableSquares() : void
  {
    $board = new Board();
    $this->assertEqualsCanonicalizing(['e3', 'e4'], $board->get_reachable_squares('e2', 'P'));
    $this->assertEqualsCanonicalizing(['e5', 'e6'], $board->get_reachable_squares('e7', 'p'));
    $this->assertEqualsCanonicalizing(['f3', 'h3'], $board->get_reachable_squares('g1', 'N'));

    $board->set_square('e2', '');
    $board->set_square('a2', '');
    $this->assertEqualsCanonicalizing(['e2', 'd3', 'c4', 'b5', 'a6'], $board->get_reachable_squares('f1', 'B'));
    $this->assertEqualsCanonicalizing(['e2', 'f3', 'g4', 'h5'], $board->get_reachable_squares('d1', 'Q'));
    $this->assertEqualsCanonicalizing(['e2'], $board->get_reachable_squares('e1', 'K'));
    $this->assertEqualsCanonicalizing(['a2', 'a3', 'a4', 'a5', 'a6', 'a7'], $board->get_reachable_squares('a1', 'R'));

    $board->set_square('c4', 'b');
    $this->assertEqualsCanonicalizing(['e2', 'd3', 'c4'], $board->get_reachable_squares('f1', 'B'));
  }

  public function testReachableSquaresPawnCapture() : void
  {
    $board = new Board();
    $board->set_square('e2', '');
    $board->set_square('e3', 'P');
    $board->set_square('c3', 'q');
    $this->assertEqualsCanonicalizing(['d3', 'd4', 'c3'], $board->get_reachable_squares('d2', 'P'));
  }

  public function testReachableSquaresEnPassant() : void
  {
    $board = new Board();
    $board->set_square('e2', '');
    $board->set_square('e4', 'P');
    $board->set_square('d7', '');
    $board->set_square('d4', 'p');
    $this->assertEqualsCanonicalizing(['d3', 'e3'], $board->get_reachable_squares('d4', 'p', 'e3'));
    $board->set_square('a2', '');
    $board->set_square('a5', 'P');
    $board->set_square('b7', '');
    $board->set_square('b5', 'p');
    $this->assertEqualsCanonicalizing(['a6', 'b6'], $board->get_reachable_squares('a5', 'P', 'b6'));
  }

  public function testPieceColor() : void
  {
    $this->assertEquals('w', Board::get_color_of_piece('K'));
    $this->assertEquals('b', Board::get_color_of_piece('k'));
  }

  public function testInvalidRegularPiece() : void
  {
    $board = new Board();
    $this->expectException(ParseException::class);
    $board->get_defended_squares('e4', 'A');
  }

}

<?php

use PHPUnit\Framework\TestCase;
use Onspli\Chess;

/**
 * @covers Onspli\Chess\FEN
 */
final class FENTest extends TestCase
{

  public function testSquareValidationValidSquares() : void
  {
    $this->assertNull(self::invokePrivateStaticMethod(Chess\FEN::class, 'validate_square', ['a1']));
    $this->assertNull(self::invokePrivateStaticMethod(Chess\FEN::class, 'validate_square', ['h1']));
    $this->assertNull(self::invokePrivateStaticMethod(Chess\FEN::class, 'validate_square', ['a8']));
    $this->assertNull(self::invokePrivateStaticMethod(Chess\FEN::class, 'validate_square', ['h8']));
  }

  public function testSquareValidationInvalidSquare() : void
  {
    $this->expectException(Chess\ExceptionParse::class);
    self::invokePrivateStaticMethod(Chess\FEN::class, 'validate_square', ['a1x']);
  }

  public function testSquareValidationInvalidFile1() : void
  {
    $this->expectException(Chess\ExceptionParse::class);
    self::invokePrivateStaticMethod(Chess\FEN::class, 'validate_square', ['A1']);
  }

  public function testSquareValidationInvalidFile2() : void
  {
    $this->expectException(Chess\ExceptionParse::class);
    self::invokePrivateStaticMethod(Chess\FEN::class, 'validate_square', ['11']);
  }

  public function testSquareValidationInvalidFile3() : void
  {
    $this->expectException(Chess\ExceptionParse::class);
    self::invokePrivateStaticMethod(Chess\FEN::class, 'validate_square', ['i1']);
  }

  public function testSquareValidationInvalidRank1() : void
  {
    $this->expectException(Chess\ExceptionParse::class);
    self::invokePrivateStaticMethod(Chess\FEN::class, 'validate_square', ['aa']);
  }

  public function testSquareValidationInvalidRank2() : void
  {
    $this->expectException(Chess\ExceptionParse::class);
    self::invokePrivateStaticMethod(Chess\FEN::class, 'validate_square', ['a0']);
  }

  public function testSquareValidationInvalidRank3() : void
  {
    $this->expectException(Chess\ExceptionParse::class);
    self::invokePrivateStaticMethod(Chess\FEN::class, 'validate_square', ['a9']);
  }

  public function testCoordsToSquare() : void
  {
    $this->assertEquals('a1', self::invokePrivateStaticMethod(Chess\FEN::class, 'square', [1, 1]));
    $this->assertEquals('a8', self::invokePrivateStaticMethod(Chess\FEN::class, 'square', [1, 8]));
    $this->assertEquals('h1', self::invokePrivateStaticMethod(Chess\FEN::class, 'square', [8, 1]));
    $this->assertEquals('h8', self::invokePrivateStaticMethod(Chess\FEN::class, 'square', [8, 8]));
  }

  public function testCoordsToSquareInvalidRank1() : void
  {
    $this->expectException(Chess\ExceptionParse::class);
    self::invokePrivateStaticMethod(Chess\FEN::class, 'square', [1, 0]);
  }

  public function testCoordsToSquareInvalidRank2() : void
  {
    $this->expectException(Chess\ExceptionParse::class);
    self::invokePrivateStaticMethod(Chess\FEN::class, 'square', [1, 9]);
  }

  public function testCoordsToSquareInvalidRank3() : void
  {
    $this->expectException(Chess\ExceptionParse::class);
    self::invokePrivateStaticMethod(Chess\FEN::class, 'square', [1, 1.5]);
  }

  public function testCoordsToSquareInvalidFile1() : void
  {
    $this->expectException(Chess\ExceptionParse::class);
    self::invokePrivateStaticMethod(Chess\FEN::class, 'square', [0, 1]);
  }

  public function testCoordsToSquareInvalidFile2() : void
  {
    $this->expectException(Chess\ExceptionParse::class);
    self::invokePrivateStaticMethod(Chess\FEN::class, 'square', [9, 1]);
  }

  public function testCoordsToSquareInvalidFile3() : void
  {
    $this->expectException(Chess\ExceptionParse::class);
    self::invokePrivateStaticMethod(Chess\FEN::class, 'square', [1.5, 1]);
  }

  public function testFileFromSquare() : void
  {
    $this->assertEquals(1, self::invokePrivateStaticMethod(Chess\FEN::class, 'file', ['a1']));
    $this->assertEquals(1, self::invokePrivateStaticMethod(Chess\FEN::class, 'file', ['a2']));
    $this->assertEquals(8, self::invokePrivateStaticMethod(Chess\FEN::class, 'file', ['h7']));
  }

  public function testRankFromSquare() : void
  {
    $this->assertEquals(1, self::invokePrivateStaticMethod(Chess\FEN::class, 'rank', ['a1']));
    $this->assertEquals(1, self::invokePrivateStaticMethod(Chess\FEN::class, 'rank', ['b1']));
    $this->assertEquals(8, self::invokePrivateStaticMethod(Chess\FEN::class, 'rank', ['g8']));
  }

  public function testSetFullmoveInvalid1() : void
  {
    $this->expectException(Chess\ExceptionParse::class);
    $fen = new Chess\FEN;
    $fen->set_fullmove(0);
  }

  public function testSetFullmoveInvalid2() : void
  {
    $this->expectException(Chess\ExceptionParse::class);
    $fen = new Chess\FEN;
    $fen->set_fullmove(1.5);
  }

  public function testSetHalfmoveInvalid1() : void
  {
    $this->expectException(Chess\ExceptionParse::class);
    $fen = new Chess\FEN;
    $fen->set_halfmove(-1);
  }

  public function testSetHalfmoveInvalid2() : void
  {
    $this->expectException(Chess\ExceptionParse::class);
    $fen = new Chess\FEN;
    $fen->set_halfmove(1.5);
  }

  public function testSetFullmove() : void
  {
    $fen = new Chess\FEN;
    $this->assertEquals(1, $fen->fullmove());
    $fen->set_fullmove(4);
    $this->assertEquals(4, $fen->fullmove());
    $fen->set_fullmove('3');
    $this->assertEquals(3, $fen->fullmove());
  }

  public function testSetHalfmove() : void
  {
    $fen = new Chess\FEN;
    $this->assertEquals(0, $fen->halfmove());
    $fen->set_halfmove(4);
    $this->assertEquals(4, $fen->halfmove());
    $fen->set_halfmove('3');
    $this->assertEquals(3, $fen->halfmove());
  }

  public function testEnPassant() : void
  {
    $fen = new Chess\FEN;
    $this->assertEquals('-', $fen->en_passant());
    $fen->set_en_passant('e3');
    $this->assertEquals('e3', $fen->en_passant());
    $fen->set_en_passant('-');
    $this->assertEquals('-', $fen->en_passant());
  }

  public function testEnPassantInvalid1() : void
  {
    $fen = new Chess\FEN;
    $this->expectException(Chess\ExceptionParse::class);
    $fen->set_en_passant('e4');
  }

  public function testEnPassantInvalid2() : void
  {
    $fen = new Chess\FEN;
    $this->expectException(Chess\ExceptionParse::class);
    $fen->set_en_passant('h1');
  }

  public function testActive() : void
  {
    $fen = new Chess\FEN;
    $this->assertEquals('w', $fen->active());
    $fen->set_active('b');
    $this->assertEquals('b', $fen->active());
    $fen->set_active('w');
    $this->assertEquals('w', $fen->active());
  }

  public function testSetActiveInvalid() : void
  {
    $fen = new Chess\FEN;
    $this->expectException(Chess\ExceptionParse::class);
    $fen->set_active('x');
  }

  public function testCastling() : void
  {
    $fen = new Chess\FEN;
    $this->assertEquals('KQkq', $fen->castling());
    $fen->set_castling('-');
    $this->assertEquals('-', $fen->castling());
    $fen->set_castling('KQkq');
    $this->assertEquals('KQkq', $fen->castling());
    $fen->set_castling('k');
    $this->assertEquals('k', $fen->castling());
  }

  public function testSetCastlingInvalid() : void
  {
    $fen = new Chess\FEN;
    $this->expectException(Chess\ExceptionParse::class);
    $fen->set_castling('KQxq');
  }

  public function testCastlingAvailibility() : void
  {
    $fen = new Chess\FEN;
    $this->assertEquals(true, $fen->castling_availability('K'));
    $this->assertEquals(true, $fen->castling_availability('Q'));
    $this->assertEquals(true, $fen->castling_availability('k'));
    $this->assertEquals(true, $fen->castling_availability('q'));
    $this->assertEquals('KQkq', $fen->castling());

    $fen->set_castling_availability('k', false);
    $this->assertEquals(true, $fen->castling_availability('K'));
    $this->assertEquals(true, $fen->castling_availability('Q'));
    $this->assertEquals(false, $fen->castling_availability('k'));
    $this->assertEquals(true, $fen->castling_availability('q'));
    $this->assertEquals('KQq', $fen->castling());

    $fen->set_castling_availability('Q', false);
    $this->assertEquals(true, $fen->castling_availability('K'));
    $this->assertEquals(false, $fen->castling_availability('Q'));
    $this->assertEquals(false, $fen->castling_availability('k'));
    $this->assertEquals(true, $fen->castling_availability('q'));
    $this->assertEquals('Kq', $fen->castling());


    $fen->set_castling_availability('k', true);
    $this->assertEquals(true, $fen->castling_availability('K'));
    $this->assertEquals(false, $fen->castling_availability('Q'));
    $this->assertEquals(true, $fen->castling_availability('k'));
    $this->assertEquals(true, $fen->castling_availability('q'));
    $this->assertEquals('Kkq', $fen->castling());

    $fen->set_castling_availability('K', false);
    $fen->set_castling_availability('Q', false);
    $fen->set_castling_availability('k', false);
    $fen->set_castling_availability('q', false);
    $this->assertEquals(false, $fen->castling_availability('K'));
    $this->assertEquals(false, $fen->castling_availability('Q'));
    $this->assertEquals(false, $fen->castling_availability('k'));
    $this->assertEquals(false, $fen->castling_availability('q'));
    $this->assertEquals('-', $fen->castling());

    $fen->set_castling_availability('k', true);
    $fen->set_castling_availability('K', true);
    $this->assertEquals(true, $fen->castling_availability('K'));
    $this->assertEquals(false, $fen->castling_availability('Q'));
    $this->assertEquals(true, $fen->castling_availability('k'));
    $this->assertEquals(false, $fen->castling_availability('q'));
    $this->assertEquals('Kk', $fen->castling());
  }

  public function testBoard() : void
  {
    $fen = new Chess\FEN;
    $fen->set_board('rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R');
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R', $fen->board());

    $fen->set_board(' rnbqkbnr  /pp 1ppppp/8/2p5/4P3/5N2/PPPP1PPP/ RNBQKB1R ');
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R', $fen->board());

    $this->assertEquals('p', $fen->piece('b7'));
    $this->assertEquals('', $fen->piece('b5'));
    $this->assertEquals('p', $fen->piece('c5'));
    $this->assertEquals('', $fen->piece('d5'));
    $this->assertEquals('P', $fen->piece('e4'));
    $this->assertEquals('R', $fen->piece('a1'));

    $fen->set_piece('c5', '');
    $this->assertEquals('', $fen->piece('c5'));
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/8/4P3/5N2/PPPP1PPP/RNBQKB1R', $fen->board());

    $fen->set_piece('c5', 'R');
    $this->assertEquals('R', $fen->piece('c5'));
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/2R5/4P3/5N2/PPPP1PPP/RNBQKB1R', $fen->board());
  }

  public function testInitializationAndExport() : void
  {
    $fen = new Chess\FEN;
    $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', $fen->export());
    $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR', $fen->board());
    $this->assertEquals('w', $fen->active());
    $this->assertEquals('KQkq', $fen->castling());
    $this->assertEquals('-', $fen->en_passant());
    $this->assertEquals(0, $fen->halfmove());
    $this->assertEquals(1, $fen->fullmove());

    $fen = new Chess\FEN('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR b KQq c6 1 2');
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR b KQq c6 1 2', $fen->export());
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR', $fen->board());
    $this->assertEquals('b', $fen->active());
    $this->assertEquals('KQq', $fen->castling());
    $this->assertEquals('c6', $fen->en_passant());
    $this->assertEquals(1, $fen->halfmove());
    $this->assertEquals(2, $fen->fullmove());

  }

  private static function invokePrivateMethod(&$object, $methodName, array $parameters = array())
  {
      $reflection = new \ReflectionClass(get_class($object));
      $method = $reflection->getMethod($methodName);
      $method->setAccessible(true);

      return $method->invokeArgs($object, $parameters);
  }

  private static function invokePrivateStaticMethod($class, $methodName, array $parameters = array())
  {
      $reflection = new \ReflectionClass($class);
      $method = $reflection->getMethod($methodName);
      $method->setAccessible(true);

      return $method->invokeArgs(null, $parameters);
  }



}

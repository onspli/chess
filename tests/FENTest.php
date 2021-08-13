<?php
namespace Onspli\Chess;
use PHPUnit\Framework\TestCase;

/**
 * @covers Onspli\Chess\FEN
 */
final class FENTest extends TestCase
{

  public function testSetFullmoveInvalid1() : void
  {
    $this->expectException(ParseException::class);
    $fen = new FEN;
    $fen->set_fullmove(0);
  }

  public function testSetFullmoveInvalid2() : void
  {
    $this->expectException(ParseException::class);
    $fen = new FEN;
    $fen->set_fullmove(1.5);
  }

  public function testSetHalfmoveInvalid1() : void
  {
    $this->expectException(ParseException::class);
    $fen = new FEN;
    $fen->set_halfmove(-1);
  }

  public function testSetHalfmoveInvalid2() : void
  {
    $this->expectException(ParseException::class);
    $fen = new FEN;
    $fen->set_halfmove(1.5);
  }

  public function testSetFullmove() : void
  {
    $fen = new FEN;
    $this->assertEquals(1, $fen->fullmove());
    $fen->set_fullmove(4);
    $this->assertEquals(4, $fen->fullmove());
    $fen->set_fullmove('3');
    $this->assertEquals(3, $fen->fullmove());
  }

  public function testSetHalfmove() : void
  {
    $fen = new FEN;
    $this->assertEquals(0, $fen->halfmove());
    $fen->set_halfmove(4);
    $this->assertEquals(4, $fen->halfmove());
    $fen->set_halfmove('3');
    $this->assertEquals(3, $fen->halfmove());
  }

  public function testEnPassant() : void
  {
    $fen = new FEN;
    $this->assertEquals('-', $fen->en_passant());
    $fen->set_en_passant('e3');
    $this->assertEquals('e3', $fen->en_passant());
    $fen->set_en_passant('-');
    $this->assertEquals('-', $fen->en_passant());
  }

  public function testEnPassantInvalid1() : void
  {
    $fen = new FEN;
    $this->expectException(ParseException::class);
    $fen->set_en_passant('e4');
  }

  public function testEnPassantInvalid2() : void
  {
    $fen = new FEN;
    $this->expectException(ParseException::class);
    $fen->set_en_passant('h1');
  }

  public function testActive() : void
  {
    $fen = new FEN;
    $this->assertEquals('w', $fen->active());
    $fen->set_active('b');
    $this->assertEquals('b', $fen->active());
    $fen->set_active('w');
    $this->assertEquals('w', $fen->active());
  }

  public function testSetActiveInvalid() : void
  {
    $fen = new FEN;
    $this->expectException(ParseException::class);
    $fen->set_active('x');
  }

  public function testCastling() : void
  {
    $fen = new FEN;
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
    $fen = new FEN;
    $this->expectException(ParseException::class);
    $fen->set_castling('KQxq');
  }

  public function testSetCastlingAvailibilityInvalid1() : void
  {
    $fen = new FEN;
    $this->expectException(ParseException::class);
    $fen->castling_availability('x');
  }

  public function testSetCastlingAvailibilityInvalid2() : void
  {
    $fen = new FEN;
    $this->expectException(ParseException::class);
    $fen->set_castling_availability('x', true);
  }

  public function testCastlingAvailibility() : void
  {
    $fen = new FEN;
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

  public function testFiftyMove() : void
  {
    $fen = new FEN;
    $fen->set_halfmove(100);
    $this->assertTrue($fen->is_fifty_move());
    $fen->set_halfmove(99);
    $this->assertFalse($fen->is_fifty_move());
  }

  public function testBoard() : void
  {
    $fen = new FEN;
    $fen->set_board('rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R');
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R', $fen->board());

    $fen->set_board(' rnbqkbnr  /pp 1ppppp/8/2p5/4P3/5N2/PPPP1PPP/ RNBQKB1R ');
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R', $fen->board());
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R', $fen->board(true)->export());

    $this->assertEquals('p', $fen->square('b7'));
    $this->assertEquals('', $fen->square('b5'));
    $this->assertEquals('p', $fen->square('c5'));
    $this->assertEquals('', $fen->square('d5'));
    $this->assertEquals('P', $fen->square('e4'));
    $this->assertEquals('R', $fen->square('a1'));

    $fen->set_square('c5', '');
    $this->assertEquals('', $fen->square('c5'));
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/8/4P3/5N2/PPPP1PPP/RNBQKB1R', $fen->board());

    $fen->set_square('c5', 'R');
    $this->assertEquals('R', $fen->square('c5'));
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/2R5/4P3/5N2/PPPP1PPP/RNBQKB1R', $fen->board());
  }

  public function testInitializationAndExport() : void
  {
    $fen = new FEN;
    $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', $fen->export());
    $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR', $fen->board());
    $this->assertEquals('w', $fen->active());
    $this->assertEquals('KQkq', $fen->castling());
    $this->assertEquals('-', $fen->en_passant());
    $this->assertEquals(0, $fen->halfmove());
    $this->assertEquals(1, $fen->fullmove());

    $fen = new FEN('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR b KQq c6 1 2');
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR b KQq c6 1 2', $fen->export());
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR', $fen->board());
    $this->assertEquals('b', $fen->active());
    $this->assertEquals('KQq', $fen->castling());
    $this->assertEquals('c6', $fen->en_passant());
    $this->assertEquals(1, $fen->halfmove());
    $this->assertEquals(2, $fen->fullmove());
  }

  public function testInvalidFen1() : void
  {
    $this->expectException(ParseException::class);
    $fen = new Fen('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1 invalid');
  }

  public function testCheckDetection() : void
  {
    $fen = new FEN;
    $this->assertFalse($fen->is_check());

    $fen->set_active('b');
    $this->assertFalse($fen->is_check());

    $fen = new FEN;
    $fen->set_board('8/8/8/8/8/8/3P4/4K3');
    $this->assertFalse($fen->is_check());
    $fen->set_board('8/8/8/8/8/8/3p4/4K3');
    $this->assertTrue($fen->is_check());

    $fen->set_board('8/8/8/8/8/8/2N5/4K3');
    $this->assertFalse($fen->is_check());
    $fen->set_board('8/8/8/8/8/8/2n5/4K3');
    $this->assertTrue($fen->is_check());

    $fen->set_board('4R3/8/8/8/8/8/8/4K3');
    $this->assertFalse($fen->is_check());
    $fen->set_board('4r3/8/8/8/8/8/8/4K3');
    $this->assertTrue($fen->is_check());

    $fen->set_board('8/8/8/B7/8/8/8/4K3');
    $this->assertFalse($fen->is_check());
    $fen->set_board('8/8/8/b7/8/8/8/4K3');
    $this->assertTrue($fen->is_check());

    $fen->set_board('8/8/8/8/7Q/8/8/4K3');
    $this->assertFalse($fen->is_check());
    $fen->set_board('8/8/8/8/7q/8/8/4K3');
    $this->assertTrue($fen->is_check());

    $fen->set_board('8/8/8/8/7q/8/5n2/4K3');
    $this->assertFalse($fen->is_check());
  }

  public function testCheckTwoKings1() : void
  {
    $this->expectException(ChessException::class);
    $fen = new FEN;
    $fen->set_board('8/8/8/8/8/8/8/KK4kk');
    $fen->is_check();
  }

  public function testCheckTwoKings2() : void
  {
    $this->expectException(ChessException::class);
    $fen = new FEN;
    $fen->set_board('8/8/8/8/8/8/8/KK4kk');
    $fen->is_check();
  }

  public function testCheckAdjacentKings() : void
  {
    $this->expectException(ChessException::class);
    $fen = new FEN;
    $fen->set_board('8/8/8/8/8/8/8/Kk6');
    $fen->is_check();
  }


}

<?php
namespace Onspli\Chess;
use PHPUnit\Framework\TestCase;

/**
 * @covers Onspli\Chess\FEN
 */
final class FENTest extends TestCase
{

  public function testStringable() : void
  {
      $fen = new FEN;
      $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', (string) $fen);
  }

  public function testCopy() : void
  {
    $fen = new FEN;
    $fen2 = clone $fen;
    $fen->set_square('e4', 'Q');
    $fen->set_fullmove(5);
    $fen->set_en_passant('e3');
    $this->assertEquals('', $fen2->get_square('e4'));
    $this->assertEquals(1, $fen2->get_fullmove());
    $this->assertEquals('-', $fen2->get_en_passant());
  }

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
    $this->assertEquals(1, $fen->get_fullmove());
    $fen->set_fullmove(4);
    $this->assertEquals(4, $fen->get_fullmove());
    $fen->set_fullmove('3');
    $this->assertEquals(3, $fen->get_fullmove());
  }

  public function testSetHalfmove() : void
  {
    $fen = new FEN;
    $this->assertEquals(0, $fen->get_halfmove());
    $fen->set_halfmove(4);
    $this->assertEquals(4, $fen->get_halfmove());
    $fen->set_halfmove('3');
    $this->assertEquals(3, $fen->get_halfmove());
  }

  public function testEnPassant() : void
  {
    $fen = new FEN;
    $this->assertEquals('-', $fen->get_en_passant());
    $fen->set_en_passant('e3');
    $this->assertEquals('e3', $fen->get_en_passant());
    $this->assertEquals('e3', $fen->get_en_passant(true)->export());
    $fen->set_en_passant('-');
    $this->assertEquals('-', $fen->get_en_passant());
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
    $this->assertEquals('w', $fen->get_active_color());
    $fen->set_active_color('b');
    $this->assertEquals('b', $fen->get_active_color());
    $fen->set_active_color('w');
    $this->assertEquals('w', $fen->get_active_color());
  }

  public function testSetActiveInvalid() : void
  {
    $fen = new FEN;
    $this->expectException(ParseException::class);
    $fen->set_active_color('x');
  }

  public function testCastling() : void
  {
    $fen = new FEN;
    $this->assertEquals('KQkq', $fen->get_castling());
    $fen->set_castling('-');
    $this->assertEquals('-', $fen->get_castling());
    $fen->set_castling('KQkq');
    $this->assertEquals('KQkq', $fen->get_castling());
    $fen->set_castling('k');
    $this->assertEquals('k', $fen->get_castling());
    $fen->set_castling('AHah');
    $this->assertEquals('AHah', $fen->get_castling());

    $fen = new FEN('rnbkqbrn/pppppppp/8/8/8/8/PPPPPPPP/RNBKQBRN w AGag - 0 1');
    $this->assertEquals('AGag', $fen->get_castling());

    $fen = new FEN('rnbkqbrn/pppppppp/8/8/8/8/PPPPPPPP/RNBKQBRN w Ag - 0 1');
    $this->assertEquals('Ag', $fen->get_castling());

    $fen = new FEN('rnbkqbrn/pppppppp/8/8/8/8/PPPPPPPP/RNBKQBRN w - - 0 1');
    $this->assertEquals('-', $fen->get_castling());
  }

  public function testSetCastlingInvalid1() : void
  {
    $fen = new FEN;
    $this->expectException(ParseException::class);
    $fen->set_castling('KQxq');
  }

  public function testSetCastlingInvalid2() : void
  {
    $fen = new FEN;
    $this->expectException(ParseException::class);
    $fen->set_castling('KQah');
  }

  public function testSetCastlingInvalid3() : void
  {
    $this->expectException(ParseException::class);
    $fen = new FEN('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w AHbh - 0 1');
  }

  public function testSetCastlingInvalid4() : void
  {
    $this->expectException(ParseException::class);
    $fen = new FEN('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w AHag - 0 1');
  }

  public function testSetCastlingInvalid5() : void
  {
    $this->expectException(ParseException::class);
    $fen = new FEN('rnbkqbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBKqBNR w KQkq - 0 1');
  }

  public function testSetCastlingInvalid6() : void
  {
    $this->expectException(ParseException::class);
    $fen = new FEN('nrbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/NRBQKBNR w KQkq - 0 1');
  }

  public function testSetCastlingInvalid7() : void
  {
    $this->expectException(ParseException::class);
    $fen = new FEN('rnbqkbrn/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBRN w KQkq - 0 1');
  }

  public function testSetCastlingInvalid8() : void
  {
    $this->expectException(ParseException::class);
    $fen = new FEN('rnbqkbrn/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBRN w AHah - 0 1');
  }

  public function testSetCastlingInvalid9() : void
  {
    $this->expectException(ParseException::class);
    $fen = new FEN('rnbrkbqn/pppppppp/8/8/8/8/PPPPPPPP/RNBRKBQN w ADad - 0 1');
  }

  public function testSetCastlingInvalid10() : void
  {
    $this->expectException(ParseException::class);
    $fen = new FEN('qnbnkbrr/pppppppp/8/8/8/8/PPPPPPPP/qNBNKBRR w GHgh - 0 1');
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
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R', $fen->get_board());

    $fen->set_board(' rnbqkbnr  /pp 1ppppp/8/2p5/4P3/5N2/PPPP1PPP/ RNBQKB1R ');
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R', $fen->get_board());
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R', $fen->get_board(true)->export());

    $this->assertEquals('p', $fen->get_square('b7'));
    $this->assertEquals('', $fen->get_square('b5'));
    $this->assertEquals('p', $fen->get_square('c5'));
    $this->assertEquals('', $fen->get_square('d5'));
    $this->assertEquals('P', $fen->get_square('e4'));
    $this->assertEquals('R', $fen->get_square('a1'));

    $fen->set_square('c5', '');
    $this->assertEquals('', $fen->get_square('c5'));
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/8/4P3/5N2/PPPP1PPP/RNBQKB1R', $fen->get_board());

    $fen->set_square('c5', 'R');
    $this->assertEquals('R', $fen->get_square('c5'));
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/2R5/4P3/5N2/PPPP1PPP/RNBQKB1R', $fen->get_board());
  }

  public function testInitializationAndExport() : void
  {
    $fen = new FEN;
    $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', $fen->export());
    $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', $fen->export_short());
    $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR', $fen->get_board());
    $this->assertEquals('w', $fen->get_active_color());
    $this->assertEquals('KQkq', $fen->get_castling());
    $this->assertEquals('-', $fen->get_en_passant());
    $this->assertEquals(0, $fen->get_halfmove());
    $this->assertEquals(1, $fen->get_fullmove());

    $fen = new FEN('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR b KQq c6 1 2');
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR b KQq c6 1 2', $fen->export());
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR', $fen->get_board());
    $this->assertEquals('b', $fen->get_active_color());
    $this->assertEquals('KQq', $fen->get_castling());
    $this->assertEquals('c6', $fen->get_en_passant());
    $this->assertEquals(1, $fen->get_halfmove());
    $this->assertEquals(2, $fen->get_fullmove());

    $fen = new FEN('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -');
    $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', $fen->export());

    // just assert it doesnt throw exceptions
    $fen->preview();
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
    $fen->set_active_color('b');
    $this->assertFalse($fen->is_check());

    $fen->set_board('7k/6P1/8/8/8/8/3P4/4K3');
    $fen->set_castling('-');
    $this->assertTrue($fen->is_check());
    $fen->set_active_color('w');
    $this->assertFalse($fen->is_check());
  }

  public function testMove() : void
  {
    $fen = new FEN;
    $fen->move('e4');
    $this->assertEquals('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3 0 1', $fen->export());
    $fen->move('e5');
    $this->assertEquals('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2', $fen->export());
    $fen->move('a3');
    $this->assertEquals('rnbqkbnr/pppp1ppp/8/4p3/4P3/P7/1PPP1PPP/RNBQKBNR b KQkq - 0 2', $fen->export());
    $fen->move('Nc6');
    $this->assertEquals('r1bqkbnr/pppp1ppp/2n5/4p3/4P3/P7/1PPP1PPP/RNBQKBNR w KQkq - 1 3', $fen->export());

    // two pseudolegal moves Ne2, one knight pinned
    $fen = new FEN("rn1qk1nr/pp1b1ppp/4p3/1B1p4/1b1P1B2/2N5/PPP2PPP/R2QK1NR w KQkq - 4 7");
    $fen->move('Ne2');
    $this->assertEquals('rn1qk1nr/pp1b1ppp/4p3/1B1p4/1b1P1B2/2N5/PPP1NPPP/R2QK2R', $fen->get_board());

  }

  public function testMoveToCheck() : void
  {
    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('1q5k/8/8/8/8/8/8/K7');
    $fen->set_castling('-');
    $this->expectException(RulesException::class);
    $fen->move('Kb1');
  }

  public function testMoveTargetNotReachableByPiece() : void
  {
    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('1q5k/8/8/8/8/8/8/K7');
    $fen->set_castling('-');
    $this->expectException(RulesException::class);
    $fen->move('Ka3');
  }

  public function testPromotion() : void
  {
    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('1q5k/P7/8/8/8/8/8/K7');
    $fen->set_castling('-');
    $fen->move('a8=Q');
    $this->assertEquals('Qq5k/8/8/8/8/8/8/K7', $fen->get_board());

    $fen = new FEN('2Q5/8/8/8/p3Q3/P7/7p/1K4k1 b - - 0 53');
    $fen->move('h1=Q');
    $this->assertEquals('2Q5/8/8/8/p3Q3/P7/8/1K4kq', $fen->get_board());
  }

  public function testPromotionNotSpecified() : void
  {
    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('1q5k/P7/8/8/8/8/8/K7');
    $fen->set_castling('-');
    $this->expectException(RulesException::class);
    $fen->move('a8');
  }

  public function testAmbiguousMoveException() : void {
    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('1q5k/8/8/8/8/8/N3N3/K7');
    $fen->set_castling('-');
    $this->expectException(RulesException::class);
    $fen->move('Nc3');
  }

  public function testAmbiguousMoveByFile() : void {
    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('1q5k/8/8/8/8/8/N3N3/K7');
    $fen->set_castling('-');
    $fen->move('Nac3');
    $this->assertEquals('1q5k/8/8/8/8/2N5/4N3/K7', $fen->get_board());
  }

  public function testAmbiguousMoveByRank() : void {
    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('1q5k/8/8/8/4N3/8/4N3/K7');
    $fen->set_castling('-');
    $fen->move('N4c3');
    $this->assertEquals('1q5k/8/8/8/8/2N5/4N3/K7', $fen->get_board());
  }

  public function testCaptureOnEmptySquareException() : void
  {
    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('1q5k/8/8/8/8/8/8/K7');
    $fen->set_castling('-');
    $this->expectException(RulesException::class);
    $fen->move('Kxa2');
  }

  public function testCaptureEnPassant() : void
  {
    $fen = new FEN('rnbqkbnr/ppp1pppp/8/8/3pP3/8/PPPP1PPP/RNBQKBNR b KQkq e3 0 1');
    $fen->move('dxe3');
    $this->assertEquals('rnbqkbnr/ppp1pppp/8/8/8/4p3/PPPP1PPP/RNBQKBNR', $fen->get_board());

    $fen = new FEN('rnbqkbnr/pppp1ppp/8/3Pp3/8/8/PPP1PPPP/RNBQKBNR w KQkq e6 0 1');
    $fen->move('dxe6');
    $this->assertEquals('rnbqkbnr/pppp1ppp/4P3/8/8/8/PPP1PPPP/RNBQKBNR', $fen->get_board());

    $fen = new FEN('rnbqkbnr/pppp1ppp/8/3Pp3/8/8/PPP1PPPP/RNBQKBNR w KQkq e6 0 1');
    $fen->move('dxe6');
    $this->assertEquals('rnbqkbnr/pppp1ppp/4P3/8/8/8/PPP1PPPP/RNBQKBNR', $fen->get_board());

    // capture on en passant square by another piece
    $fen = new FEN('rnbqkbnr/pppp1ppp/8/3PpB2/8/8/PPP1PPPP/RNBQKBNR w KQkq e6 0 1');
    $fen->move('Be6');
    $this->assertEquals('rnbqkbnr/pppp1ppp/4B3/3Pp3/8/8/PPP1PPPP/RNBQKBNR', $fen->get_board());
  }

  public function testTargetSquareOccupied() : void
  {
    $fen = new FEN;
    $this->expectException(RulesException::class);
    $fen->move('Ra2');
  }

  public function testCannotCaptureOwnPiece() : void
  {
    $fen = new FEN;
    $this->expectException(RulesException::class);
    $fen->move('Rxa2');
  }

  public function testMovingRooksDismissCastling() : void
  {
    $fen = new FEN;
    $fen->move('a4');
    $fen->move('a5');
    $this->assertEquals('KQkq', $fen->get_castling());
    $fen->move('Ra3');
    $this->assertEquals('Kkq', $fen->get_castling());
    $fen->move('Ra7');
    $this->assertEquals('Kk', $fen->get_castling());
    $fen->move('h4');
    $fen->move('h5');
    $fen->move('Rh2');
    $this->assertEquals('k', $fen->get_castling());
    $fen->move('Rh6');
    $this->assertEquals('-', $fen->get_castling());
  }

  public function testBongcloud() : void
  {
    $fen = new FEN;
    $fen->move('e4');
    $fen->move('e5');
    $this->assertEquals('KQkq', $fen->get_castling());
    $fen->move('Ke2');
    $this->assertEquals('kq', $fen->get_castling());
    $fen->move('Ke7');
    $this->assertEquals('-', $fen->get_castling());
  }

  public function testCastlingKingside() : void
  {
    $fen = new FEN;
    $fen->move('g3');
    $fen->move('g6');
    $fen->move('Nf3');
    $fen->move('Nf6');
    $fen->move('Bg2');
    $fen->move('Bg7');
    $fen->move('O-O');
    $fen->move('O-O');
    $this->assertEquals('-', $fen->get_castling());
    $this->assertEquals('rnbq1rk1/ppppppbp/5np1/8/8/5NP1/PPPPPPBP/RNBQ1RK1', $fen->get_board());
  }

  public function testCastlingQueenside() : void
  {
    $fen = new FEN;
    $fen->move('d4');
    $fen->move('d5');
    $fen->move('b3');
    $fen->move('b6');
    $fen->move('Nc3');
    $fen->move('Nc6');
    $fen->move('Bb2');
    $fen->move('Bb7');
    $fen->move('Qd2');
    $fen->move('Qd7');
    $fen->move('O-O-O');
    $fen->move('O-O-O');
    $this->assertEquals('-', $fen->get_castling());
    $this->assertEquals('2kr1bnr/pbpqpppp/1pn5/3p4/3P4/1PN5/PBPQPPPP/2KR1BNR', $fen->get_board());
  }

  public function testCastlingKingsideNotAvailableWhite() : void
  {
    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('r3k2r/8/8/8/8/8/8/R3K2R');
    $fen->set_castling('Qkq');
    $this->expectException(RulesException::class);
    $fen->move('O-O');
  }

  public function testCastlingKingsideNotAvailableBlack() : void
  {
    $fen = new FEN;
    $fen->set_active_color('b');
    $fen->set_board('r3k2r/8/8/8/8/8/8/R3K2R');
    $fen->set_castling('KQq');
    $this->expectException(RulesException::class);
    $fen->move('O-O');
  }

  public function testCastlingQueensideNotAvailableWhite() : void
  {
    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('r3k2r/8/8/8/8/8/8/R3K2R');
    $fen->set_castling('Kkq');
    $this->expectException(RulesException::class);
    $fen->move('O-O-O');
  }

  public function testCastlingQueensideNotAvailableBlack() : void
  {
    $fen = new FEN;
    $fen->set_active_color('b');
    $fen->set_board('r3k2r/8/8/8/8/8/8/R3K2R');
    $fen->set_castling('KQk');
    $this->expectException(RulesException::class);
    $fen->move('O-O-O');
  }

  // Capturing black's rook prevents castling
  public function testCastlingKingsideBlackCapture() : void
  {
    $fen = new FEN('r3k2r/8/8/4B3/4B3/8/8/4K3 w kq - 0 1');
    $fen->move('Bxh8');
    $this->assertEquals('q', $fen->get_castling());
    $this->expectException(RulesException::class);
    $fen->move('O-O');
  }

  // Capturing black's rook prevents castling
  public function testCastlingQueensideBlackCapture() : void
  {
    $fen = new FEN('r3k2r/8/8/4B3/4B3/8/8/4K3 w kq - 0 1');
    $fen->move('Bxa8');
    $this->assertEquals('k', $fen->get_castling());
    $this->expectException(RulesException::class);
    $fen->move('O-O-O');
  }

  // Capturing white's rook prevents castling
  public function testCastlingKingsideWhiteCapture() : void
  {
    $fen = new FEN('4k3/8/8/4b3/4b3/8/8/R3K2R b KQ - 0 1');
    $fen->move('Bxh1');
    $this->assertEquals('Q', $fen->get_castling());
    $this->expectException(RulesException::class);
    $fen->move('O-O');
  }

  // Capturing white's rook prevents castling
  public function testCastlingQueensideWhiteCapture() : void
  {
    $fen = new FEN('4k3/8/8/4b3/4b3/8/8/R3K2R b KQ - 0 1');
    $fen->move('Bxa1');
    $this->assertEquals('K', $fen->get_castling());
    $this->expectException(RulesException::class);
    $fen->move('O-O-O');
  }

  public function testCastlingKingsidePiecesInWayWhite() : void
  {
    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('rn2kb1r/8/8/8/8/8/8/RN2KB1R');
    $fen->set_castling('KQkq');
    $this->expectException(RulesException::class);
    $fen->move('O-O');
  }

  public function testCastlingQueensidePiecesInWayWhite() : void
  {
    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('rn2kb2/8/8/7r/7R/8/8/RN2KB2');
    $fen->set_castling('Qq');
    $this->expectException(RulesException::class);
    $fen->move('O-O-O');
  }

  public function testCastlingKingsideCheckWhite() : void
  {
    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('r3k2r/4q3/8/8/8/8/8/R3K2R');
    $fen->set_castling('KQkq');
    $this->expectException(RulesException::class);
    $fen->move('O-O');
  }

  public function testCastlingQueensideCheckWhite() : void
  {
    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('r3k2r/4q3/8/8/8/8/8/R3K2R');
    $fen->set_castling('KQkq');
    $this->expectException(RulesException::class);
    $fen->move('O-O-O');
  }

  public function testCastlingKingsideCheckOnTheWayWhite() : void
  {
    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('r3k2r/5q2/8/8/8/8/8/R3K2R');
    $fen->set_castling('KQkq');
    $this->expectException(RulesException::class);
    $fen->move('O-O');
  }

  public function testCastlingQueensideCheckOnTheWayWhite() : void
  {
    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('r3k2r/3q4/8/8/8/8/8/R3K2R');
    $fen->set_castling('KQkq');
    $this->expectException(RulesException::class);
    $fen->move('O-O-O');
  }

  public function testCastlingKingsideCheckOnTargetWhite() : void
  {
    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('r3k2r/6q1/8/8/8/8/8/R3K2R');
    $fen->set_castling('KQkq');
    $this->expectException(RulesException::class);
    $fen->move('O-O');
  }

  public function testCastlingQueensideCheckOnTargetWhite() : void
  {
    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('r3k2r/2q5/8/8/8/8/8/R3K2R');
    $fen->set_castling('KQkq');
    $this->expectException(RulesException::class);
    $fen->move('O-O-O');
  }

  public function testPossibleMoves() : void
  {
    $fen = new FEN;
    $this->assertEqualsCanonicalizing(['Nf3', 'Nh3', 'Na3', 'Nc3', 'a3', 'a4', 'b3', 'b4', 'c3', 'c4', 'd3', 'd4', 'e3', 'e4', 'f3', 'f4', 'g3', 'g4', 'h3', 'h4'], $fen->get_legal_moves());

    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('7k/8/8/8/2n5/1P1P4/8/K7');
    $this->assertEqualsCanonicalizing(['Ka2', 'Kb1', 'bxc4', 'dxc4', 'b4', 'd4'], $fen->get_legal_moves());

    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('7k/8/8/8/2n5/1P6/8/K7');
    $this->assertEqualsCanonicalizing(['Ka2', 'Kb1', 'bxc4', 'b4'], $fen->get_legal_moves());
  }

  public function testPossibleMovesAbiguious() : void
  {
    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('2q4k/8/8/8/8/8/8/NK2N3');
    $this->assertEqualsCanonicalizing(['Ka2', 'Kb2', 'Nac2', 'Nec2', 'Nb3', 'Nd3', 'Nf3', 'Ng2'], $fen->get_legal_moves());

    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('2q4k/8/8/N7/8/8/8/NK6');
    $this->assertEqualsCanonicalizing(['Ka2', 'Kb2', 'N1b3', 'Nc2', 'N5b3', 'Nc4', 'Nc6', 'Nb7'], $fen->get_legal_moves());

    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('2q4k/8/8/8/8/N7/8/NK2N3');
    $this->assertEqualsCanonicalizing(['Ka2', 'Kb2', 'Na1c2', 'Na3c2', 'Ne1c2', 'Nb3', 'Nd3', 'Nf3', 'Ng2', 'Nc4', 'Nb5'], $fen->get_legal_moves());

  }

  public function testPossibleMovesPromotion() : void
  {
    $fen = new FEN;
    $fen->set_active_color('w');
    $fen->set_board('1q5k/P7/8/8/8/8/8/K7');
    $this->assertEqualsCanonicalizing(['Ka2', 'a8=N', 'a8=B', 'a8=R', 'a8=Q', 'axb8=N', 'axb8=B', 'axb8=R', 'axb8=Q'], $fen->get_legal_moves());
  }


  public function testMate() : void
  {
    $fen = new FEN('4R1k1/ppp2ppp/8/8/PQ1r4/1P6/6PP/1R4K1 b - - 1 26');
    $this->assertTrue($fen->is_mate());
  }

  public function testStalemate() : void
  {
    $fen = new FEN('5k2/8/4QP2/8/8/8/6K1/8 b - - 0 57');
    $this->assertTrue($fen->is_stalemate());
  }

}

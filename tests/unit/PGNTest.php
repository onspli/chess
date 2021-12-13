<?php
namespace Onspli\Chess;
use PHPUnit\Framework\TestCase;

/**
 * @covers Onspli\Chess\PGN
 */
final class PGNTest extends TestCase
{

  protected $samples = [
    '[Event "F/S Return Match"]
[Site "Belgrade, Serbia JUG"]
[Date "1992.11.04"]
[Round "29"]
[White "Fischer, Robert J."]
[Black "Spassky, Boris V."]
[Result "1/2-1/2"]

1. e4 e5 2. Nf3 Nc6 3. Bb5 a6 {This opening is called the Ruy Lopez.}
4. Ba4 {What a move!} 4... Nf6 5. O-O Be7 6. Re1 b5 7. Bb3 d6 8. c3 O-O 9. h3 Nb8 10. d4 Nbd7
11. c4 c6 12. cxb5 axb5 13. Nc3 Bb7 14. Bg5 b4 15. Nb1 h6 16. Bh4 c5 17. dxe5
Nxe4 18. Bxe7 Qxe7 19. exd6 Qf6 20. Nbd2 Nxd6 21. Nc4 Nxc4 22. Bxc4 Nb6
23. Ne5 Rae8 24. Bxf7+ Rxf7 25. Nxf7 Rxe1+ 26. Qxe1 Kxf7 27. Qe3 Qg5 28. Qxg5
hxg5 29. b3 Ke6 30. a3 Kd6 31. axb4 cxb4 32. Ra5 Nd5 33. f3 Bc8 34. Kf2 Bf5
35. Ra7 g6 36. Ra6+ Kc5 37. Ke1 Nf4 38. g3 Nxh3 39. Kd2 Kb5 40. Rd6 Kc5 41. Ra6
Nf2 42. g4 Bd3 43. Re6 1/2-1/2',

'[Event "Live Chess - Chess960"]
[Site "Chess.com"]
[Date "2021.10.20"]
[Round "?"]
[White "Oleksandr_Bortnyk"]
[Black "Hikaru"]
[Result "0-1"]
[Variant "Chess960"]
[SetUp "1"]
[FEN "bbrkqrnn/pppppppp/8/8/8/8/PPPPPPPP/BBRKQRNN w FCfc -"]
[WhiteElo "2836"]
[BlackElo "3123"]
[TimeControl "180"]
[EndTime "13:37:49 PDT"]
[Termination "Hikaru won by checkmate"]
[initialSetup "bbrkqrnn/pppppppp/8/8/8/8/PPPPPPPP/BBRKQRNN w FCfc -"]

1. c4 Nf6 2. b3 c5 3. Ng3 b6 4. e4 Ng6 5. Nh3 e5 6. Nf5 Qe6 7. Ng5 Qc6 8. Nxg7
h6 9. Nh3 Nxe4 10. Nf5 Nf6 11. f3 O-O-O 12. Nxh6 d5 13. Nxf7 Rxf7 14. Bxg6 Rg7
15. Bf5+ Kb7 16. g4 Ka6 17. Bxe5 Re8 18. f4 Nxg4 19. Bxg7 Rxe1+ 20. Rxe1 d4 21.
Bxg4 Qg2 22. Rg1 Bf3+ 23. Kc2 Be4+ 24. Kd1 Qxh2 25. Be5 Bxe5 26. fxe5 Bg2 27.
Nf2 Qxg1+ 28. Kc2 Qxf2 29. Rd1 Qf4 30. Bc8+ Ka5 31. e6 Be4+ 32. Kc1 Bg6 33. Kb2
Qe4 34. Ka3 b5 35. cxb5 Qc2 36. Rg1 Qxd2 37. e7 c4 38. b6 Qb4+ 39. Kb2 Qc3+ 40.
Ka3 cxb3 41. axb3 Qc5+ 42. Ka2 Be8 43. Rg2 Kxb6 44. Be6 d3 45. Rb2 d2 46. Rxd2
Qa5+ 47. Kb2 Qxd2+ 48. Ka3 Qe3 49. Kb4 Qe4+ 50. Ka3 Qxe6 51. b4 Qxe7 52. Kb3 a5
53. Ka3 Kb5 54. Ka2 Qxb4 55. Ka1 a4 56. Ka2 a3 57. Ka1 Qb2# 0-1'

  ];

  public function testMovetextParsing() : void
  {
    $pgn = new PGN($this->samples[0]);
    $this->assertEquals('e4', $pgn->get_halfmove(PGN::get_halfmove_number(1, 'w')));
    $this->assertEquals('e5', $pgn->get_halfmove(PGN::get_halfmove_number(1, 'b')));
    $this->assertEquals('Re6', $pgn->get_halfmove(PGN::get_halfmove_number(43, 'w')));
    $this->assertEquals('e5', $pgn->get_halfmove(PGN::get_halfmove_number(1, 'b'), true)->export());
  }

  public function testTagsParsing() : void
  {
    $pgn = new PGN($this->samples[0]);
    $this->assertEquals('Fischer, Robert J.', $pgn->get_tag('White'));
    $this->assertNull($pgn->get_tag('Nonexistent'));

    $pgn = new PGN('[Invalid blabla]');
    $this->assertNull($pgn->get_tag('Invalid'));
  }

  public function testGetFen() : void
  {
    $pgn = new PGN;
    $pgn->move('e4');
    $pgn->move('e5');
    $this->assertEquals('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2', $pgn->get_current_fen());
    $this->assertEquals('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3 0 1', $pgn->get_fen_after_halfmove(1));
    $this->assertEquals('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2', $pgn->get_current_fen(true)->export());
  }

  public function testExport() : void
  {
    $pgn = new PGN;
    $pgn->set_tag("Site", "Belgrade, Serbia JUG");
    $pgn->move('e4');
    $pgn->move('e5');
    $pgn->move('Nf3');
    $this->assertEquals('[Site "Belgrade, Serbia JUG"]
1. e4 e5 2. Nf3', $pgn->export());
  }



  public function testCustomInitialPosition() : void
  {
    $pgn = new PGN;
    $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', $pgn->get_initial_fen());
    $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', $pgn->get_current_fen());
    $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', $pgn->get_fen_after_halfmove(0));

    $fen = new FEN;
    $fen->move('e4');
    $pgn->set_tag("FEN", $fen->export());
    $this->assertEquals('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3 0 1', $pgn->get_initial_fen());

    $pgn->move('e5');
    $pgn->move('Nf3');
    $this->assertEquals('[FEN "rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3 0 1"]
1... e5 2. Nf3', $pgn->export());
    $pgn->unset_tag("FEN");
    $this->assertEquals('1. e5 Nf3', $pgn->export());

    $pgn->set_tag("FEN", $fen->export());
    $pgn->unset_initial_fen();
    $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', $pgn->get_initial_fen());

  }

  public function testHalfmoveOutOfBoundsTooLarge1() : void
  {
    $pgn = new PGN($this->samples[0]);
    $this->expectException(\OutOfBoundsException::class);
    $pgn->get_fen_after_halfmove(100);
  }

  public function testHalfmoveOutOfBoundsTooSmall1() : void
  {
    $pgn = new PGN($this->samples[0]);
    $fen = new FEN;
    $fen->move('e4');
    $pgn->set_initial_fen($fen->export());
    $this->expectException(\OutOfBoundsException::class);
    $pgn->get_fen_after_halfmove(1);
  }

  public function testHalfmoveOutOfBoundsTooLarge2() : void
  {
    $pgn = new PGN($this->samples[0]);
    $this->expectException(\OutOfBoundsException::class);
    $pgn->get_halfmove(100);
  }

  public function testHalfmoveOutOfBoundsTooSmall2() : void
  {
    $pgn = new PGN($this->samples[0]);
    $fen = new FEN;
    $fen->move('e4');
    $pgn->set_initial_fen($fen->export());
    $this->expectException(\OutOfBoundsException::class);
    $pgn->get_halfmove(1);
  }

  public function testValidateMoves() : void
  {
    $pgn = new PGN($this->samples[0]);
    $this->assertNull($pgn->validate_moves());

    $pgn = new PGN('1. e5');
    $this->expectException(\Exception::class);
    $pgn->validate_moves();
  }

  public function testGetAllTags() : void
  {
    $pgn = new PGN($this->samples[0]);
    $this->assertEquals([
      "Event" => "F/S Return Match",
      "Site" => "Belgrade, Serbia JUG",
      "Date" => "1992.11.04",
      "Round" => "29",
      "White" => "Fischer, Robert J.",
      "Black" => "Spassky, Boris V.",
      "Result" => "1/2-1/2"
    ], $pgn->get_tags());
  }

  public function testChess960() : void
  {
    $pgn = new PGN($this->samples[1]);
    $this->assertEquals('4b3/8/8/1k6/8/p7/1q6/K7 w - - 2 58', $pgn->get_current_fen());
  }

}

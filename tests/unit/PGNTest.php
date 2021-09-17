<?php
namespace Onspli\Chess;
use PHPUnit\Framework\TestCase;

/**
 * @covers Onspli\Chess\PGN
 */
final class PGNTest extends TestCase
{

/*
  public function testBugs() : void
  {
    $pgn = new PGN('[Event "Live Chess"]
[Site "Chess.com"]
[Date "2021.01.28"]
[Round "?"]
[White "djkvetak"]
[Black "Nirrmall"]
[Result "1-0"]
[ECO "B01"]
[WhiteElo "1126"]
[BlackElo "1092"]
[TimeControl "3600"]
[EndTime "7:02:24 PST"]
[Termination "djkvetak won by resignation"]

1. e4 d5 2. exd5 Qxd5 3. c4 Qd8 4. d4 h6 5. Qh5 Nf6 6. Qf3 Qxd4 7. Nc3 Ng4 8.
Be3 Nxe3 9. fxe3 Qd8 10. Qg3 e6 11. Nf3 g6 12. a3 Bd6 13. Ne5 Qe7 14. Nb5 Qf6
15. O-O-O Bxe5 16. Nxc7+ Bxc7 17. Qxc7 O-O 18. Qd6 Rd8 19. Qxd8+ Qxd8 20. Rxd8+
Kg7 21. Rxc8 Kf6 22. g3 Ke7 23. Bg2 a5 24. Bxb7 Ra7 25. Rxb8 Kd7 26. Bc8+ Kc7
27. Rb7+ Rxb7 28. Bxb7 Kxb7 29. Rd1 Kb6 30. b4 a4 31. Kb1 f5 32. Rc1 Kc6 33. h3
h5 34. Re1 g5 35. h4 g4 36. e4 f4 37. gxf4 Kd6 38. f5 exf5 39. exf5 g3 40. Rg1
Ke5 41. Rxg3 Kxf5 42. b5 Kf4 43. Rg4+ Kxg4 44. b6 Kxh4 45. b7 Kg5 46. b8=Q h4
47. c5 h3 48. Qb5 Kg4 49. Qc4+ Kg3 50. Qe4 h2 51. c6 Kf2 52. c7 Kg1 53. c8=Q
h1=Q 54. Qc1+ Kh2 55. Qcxh1+ Kg3 1-0');
    $pgn->validate();
  }
  */


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
Nf2 42. g4 Bd3 43. Re6 1/2-1/2'

  ];

  public function testMovetextParsing() : void
  {
    $pgn = new PGN($this->samples[0]);
    $this->assertEquals('e4', $pgn->get_move(1, 'w'));
    $this->assertEquals('e5', $pgn->get_move(1, 'b'));
    $this->assertEquals('Re6', $pgn->get_move(43, 'w'));
    $this->assertEquals('e5', $pgn->get_move(1, 'b', true)->export());
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
    $this->assertEquals('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3 0 1', $pgn->get_fen_after_move(1, 'w'));
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

}

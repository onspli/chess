<?php
namespace Onspli\Chess;

class FENBench
{
  protected $initial_pos;

  function __construct()
  {
    $this->initial_pos = new FEN;
  }

  /**
  * @Revs(10)
  */
  public function benchConstructor()
  {
    $fen = new FEN();
  }

  /**
  * @Revs(10)
  */
  public function benchCopy()
  {
    $fen = new FEN('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR b KQq c6 1 2');
    $fen2 = clone $fen;
  }

  /**
  * @Revs(10)
  */
  public function benchMove()
  {
    $fen = new FEN("rn1qk1nr/pp1b1ppp/4p3/1B1p4/1b1P1B2/2N5/PPP2PPP/R2QK1NR w KQkq - 4 7");
    $fen->move('Ne2');
  }

  /**
  * @Revs(10)
  */
  public function benchPossibleMovesInitialPosition()
  {
    $this->initial_pos->get_legal_moves();
  }
}

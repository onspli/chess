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
  public function benchPossibleMovesInitialPosition()
  {
    $this->initial_pos->get_possible_moves();
  }
}

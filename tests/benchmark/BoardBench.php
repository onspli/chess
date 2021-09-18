<?php
namespace Onspli\Chess;

class BoardBench
{

  /**
  * @Revs(10)
  */
  public function benchConstructor()
  {
    $board = new Board('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR');
  }

  /**
  * @Revs(10)
  */
  public function benchCopy()
  {
    $board = new Board('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR');
    $board2 = clone $board;
  }

  /**
  * @Revs(10)
  */
  public function benchCheck()
  {
    $board = new Board('8/8/8/8/7q/8/5n2/4K3');
    $board->is_check('w');
  }

}

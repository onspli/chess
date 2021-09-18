<?php
namespace Onspli\Chess;

class SquareBench
{

  /**
  * @Revs(50)
  */
  public function benchConstructor()
  {
    $square = new Square('e4');
  }

  /**
  * @Revs(50)
  */
  public function benchGetRankIndex()
  {
    $square = new Square('e4');
    $square->get_rank_index();
  }

}

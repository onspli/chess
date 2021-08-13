<?php

namespace Onspli\Chess;


/**
* There are two handy notations of squares on the chess board.
* The human-readable algebraic notation (e4), and zero based coordinates (e4 = [4,3])
* familiar to programmers.
* The class helps conversion between these two notations.
* Lets also consider special null square '-' (ie for en passant).
* Square can be either constructed as new Square('e4')
* or new Square(4, 3).
*/
class Square
{
  private $rank;
  private $file;
  private $alg = '-';

  function __construct($file, $rank = null)
  {
    if ($rank === null)
    {
      $alg = $file;
      if ($alg == '-')
      {
        $file = 0;
        $rank = 0;
      }
      else
      {
        if (strlen($alg) != 2) {
          throw new ParseException;
        }
        $file = ord($alg[0]) - ord('a');
        $rank = intval($alg[1]) - 1;
      }
    }
    else
    {
      $alg = chr(ord('a') + $file) . ($rank + 1);
    }

    self::validate_range($file, $rank);
    $this->file = $file;
    $this->rank = $rank;
    $this->alg = $alg;
  }

  public function rank() : int
  {
    if ($this->is_null()) {
      throw new \OutOfBoundsException;
    }
    return $this->rank;
  }

  public function file() : int
  {
    if ($this->is_null()) {
      throw new \OutOfBoundsException;
    }
    return $this->file;
  }

  public function alg() : string
  {
    return $this->alg;
  }

  public function is_null() : bool
  {
    return $this->alg == '-';
  }

  private static function validate_range($file, $rank) : void
  {
    if (intval($file) != $file || intval($rank) != $rank) {
      throw new \OutOfBoundsException;
    }
    if ($file < 0 || $file >= 8 || $rank < 0 || $rank >= 8) {
      throw new \OutOfBoundsException;
    }
  }

}

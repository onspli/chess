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

  function __construct($file_or_alg = null, $rank = null)
  {
    if ($rank === null)
    {
      if ($file_or_alg === null) {
        $file_or_alg = '-';
      }
      $alg = $file_or_alg;
      if ($alg == '-')
      {
        $file = -1;
        $rank = -1;
      }
      else
      {
        if (strlen($alg) != 2) {
          throw new ParseException;
        }
        $file = ord($alg[0]) - ord('a');
        $rank = intval($alg[1]) - 1;
        if (!self::is_in_range($file, $rank)) {
          throw new ParseException;
        }
      }
    } else {
      $file = $file_or_alg;
    }

    $this->file = $file;
    $this->rank = $rank;
    if (self::is_in_range($file, $rank)) {
      $this->alg = chr(ord('a') + $file) . ($rank + 1);
    } else {
      $this->alg = '-';
    }
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

  public function n()
  {
    return $this->rel(0, 1);
  }

  public function w()
  {
    return $this->rel(-1, 0);
  }

  public function s()
  {
    return $this->rel(0, -1);
  }

  public function e()
  {
    return $this->rel(1, 0);
  }

  public function nw()
  {
    return $this->rel(-1, 1);
  }

  public function ne()
  {
    return $this->rel(1, 1);
  }

  public function sw()
  {
    return $this->rel(-1, -1);
  }

  public function se()
  {
    return $this->rel(1, -1);
  }

  public function rel($east, $north)
  {
    return new Square($this->file() + $east, $this->rank() + $north);
  }

  public function add_to(array &$array, bool $as_object = false) : void
  {
    if ($this->is_null() == false) {
      if ($as_object) {
        $array[] = $this;
      } else {
        $array[] = $this->alg();
      }
    }
  }

  private static function is_in_range($file, $rank) : bool
  {
    if (intval($file) != $file || intval($rank) != $rank) {
      return false;
    }
    if ($file < 0 || $file >= 8 || $rank < 0 || $rank >= 8) {
      return false;
    }
    return true;
  }

}

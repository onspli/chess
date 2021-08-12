<?php
namespace Onspli\Chess;

class Board
{
  private $board = [];

  function __construct()
  {
    $this->board = array_fill(0, 64, '');
  }

  public function square($square) : string
  {
    if (is_string($square)) $square = new Square($square);
    self::validate_square($square);
    return $this->board[$square->rank() * 8 + $square->file()];
  }

  public function square_nothrow($square) : string
  {
    try
    {
      return $this->square($square);
    }
    catch(\Exception $e) {};
    return '';
  }

  public function set_square($square, string $piece) : void
  {
    if (is_string($square)) $square = new Square($square);
    self::validate_square($square);
    self::validate_piece($piece);
    $this->board[$square->rank() * 8 + $square->file()] = $piece;
  }

  public function set_square_nothrow($square, string $piece) : void
  {
    try
    {
      $this->set_square($square, $piece);
    }
    catch(\Exception $e) {};
  }

  /**
  * Returns array of squares containing piece.
  */
  public function find(string $piece) : array
  {
    $arr = [];
    for ($rank = 0; $rank < 8; $rank ++) {
      for ($file = 0; $file < 8; $file ++) {
        $square = new Square($file, $rank);
        $p = $this->square($square);
        if ($p == $piece) {
          $arr[] = $square;
        }
      }
    }
    return $arr;
  }

  private static function validate_piece(string $piece) : void
  {
    if (!in_array($piece, ['', 'P', 'N', 'B', 'R', 'Q', 'K', 'p', 'n', 'b', 'r', 'q', 'k'])) throw new ParseExpcetion("Invalid piece '$piece'.");
  }

  private static function validate_square($square) : void
  {
    if ($square->is_null()) throw new \OutOfBoundsException;
  }

}

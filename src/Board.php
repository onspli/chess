<?php
namespace Onspli\Chess;

class Board
{
  private $board = [];

  /**
  * Piece placement (from White's perspective). Each rank is described,
  * starting with rank 8 and ending with rank 1; within each rank,
  * the contents of each square are described from file "a" through file "h".
  * Following the Standard Algebraic Notation (SAN), each piece is identified
  * by a single letter taken from the standard English names (pawn = "P",
  * knight = "N", bishop = "B", rook = "R", queen = "Q" and king = "K").
  * White pieces are designated using upper-case letters ("PNBRQK") while
  * black pieces use lowercase ("pnbrqk"). Empty squares are noted using
  * digits 1 through 8 (the number of empty squares), and "/" separates ranks.
  */
  function __construct(string $pieces = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR')
  {
    $this->board = array_fill(0, 64, '');

    $pieces = trim($pieces);
    $pieces = preg_replace('/\s+/', '', $pieces);
    $ranks = explode('/', $pieces);
    if (sizeof($ranks) != 8) {
      throw new ParseException("Wrong number of ranks " . sizeof($ranks) . ".");
    }

    $rank = 7;
    foreach ($ranks as $rank_pieces)
    {
      $file = 0;
      foreach (str_split($rank_pieces) as $piece)
      {
        if (is_numeric($piece))
        {
          $file += intval($piece);
        }
        else
        {
          $this->set_square(new Square($file, $rank), $piece);
          $file += 1;
        }
        if ($file > 8) {
          throw new ParseException("Too many pieces on rank.");
        }
      }
      $rank -= 1;
    }
  }

  public function export() : string
  {
    $pieces = '';
    for ($rank = 7; $rank >= 0; $rank --) {
      $space = 0;
      for ($file = 0; $file < 8; $file ++) {
        $piece = $this->square(new Square($file, $rank));
        if (!$piece) {
          $space++;
        } else {
          if ($space > 0) {
            $pieces .= $space;
          }
          $pieces .= $piece;
          $space = 0;
        }
      }
      if ($space > 0) {
        $pieces .= $space;
      }
      if ($rank > 0) {
        $pieces .= '/';
      }
    }
    return $pieces;
  }

  public function square($square) : string
  {
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
  * Get array of all squares attacked (or defended) by $attacking_piece being on $attacker_square.
  */
  public function attacked_squares($attacker_square, $attacking_piece, bool $as_object = false) : array
  {
    self::validate_square($attacker_square);
    self::validate_piece($attacking_piece);
    $arr = [];

    /**
    * Add square in the direction specified up to the first piece or the end of the board
    */
    $add_direction = function ($north, $east) use (&$arr, $attacker_square, $as_object) {
        $square = $attacker_square;
        while ($square = $square->rel($north, $east)) {
          $square->add_to($arr, $as_object);
          if ($square->is_null() || $this->square($square) != '') {
            break;
          }
        }
    };

    if ($attacking_piece == 'P') {
      $attacker_square->nw()->add_to($arr, $as_object);
      $attacker_square->ne()->add_to($arr, $as_object);
    } else if ($attacking_piece == 'p') {
      $attacker_square->sw()->add_to($arr, $as_object);
      $attacker_square->se()->add_to($arr, $as_object);
    } else if ($attacking_piece == 'K' || $attacking_piece == 'k') {
      $attacker_square->n()->add_to($arr, $as_object);
      $attacker_square->nw()->add_to($arr, $as_object);
      $attacker_square->w()->add_to($arr, $as_object);
      $attacker_square->sw()->add_to($arr, $as_object);
      $attacker_square->s()->add_to($arr, $as_object);
      $attacker_square->se()->add_to($arr, $as_object);
      $attacker_square->e()->add_to($arr, $as_object);
      $attacker_square->ne()->add_to($arr, $as_object);
    } else if ($attacking_piece == 'N' || $attacking_piece == 'n') {
      $attacker_square->rel(2, 1)->add_to($arr, $as_object);
      $attacker_square->rel(2, -1)->add_to($arr, $as_object);
      $attacker_square->rel(-2, 1)->add_to($arr, $as_object);
      $attacker_square->rel(-2, -1)->add_to($arr, $as_object);
      $attacker_square->rel(1, 2)->add_to($arr, $as_object);
      $attacker_square->rel(1, -2)->add_to($arr, $as_object);
      $attacker_square->rel(-1, 2)->add_to($arr, $as_object);
      $attacker_square->rel(-1, -2)->add_to($arr, $as_object);
    } else {
      if ($attacking_piece == 'B' || $attacking_piece == 'b'  || $attacking_piece == 'Q'  || $attacking_piece == 'q') {
        $add_direction(1, 1);
        $add_direction(1, -1);
        $add_direction(-1, 1);
        $add_direction(-1, -1);
      }
      if ($attacking_piece == 'R' || $attacking_piece == 'r'  || $attacking_piece == 'Q'  || $attacking_piece == 'q') {
        $add_direction(1, 0);
        $add_direction(-1, 0);
        $add_direction(0, 1);
        $add_direction(0, -1);
      }
    }

    return $arr;
  }

  /**
  * Get list of pieces on squares (including multiplicities, excluding blank squares).
  */
  public function pieces_on_squares(array $squares) : array
  {
    $arr = [];
    foreach ($squares as $square) {
      $piece = $this->square($square);
      if ($piece) {
        $arr[] = $piece;
      }
    }
    return $arr;
  }

  /**
  * Returns array of squares containing piece.
  */
  public function find(string $piece, bool $as_object = false) : array
  {
    $arr = [];
    for ($rank = 0; $rank < 8; $rank ++) {
      for ($file = 0; $file < 8; $file ++) {
        $square = new Square($file, $rank);
        $p = $this->square($square);
        if ($p == $piece) {
          $square->add_to($arr, $as_object);
        }
      }
    }
    return $arr;
  }

  private static function validate_piece(string $piece) : void
  {
    if (!in_array($piece, ['', 'P', 'N', 'B', 'R', 'Q', 'K', 'p', 'n', 'b', 'r', 'q', 'k'])) {
      throw new ParseException("Invalid piece '$piece'.");
    }
  }

  private static function validate_square(&$square) : void
  {
    if (is_string($square)) {
      $square = new Square($square);
    }
    if ($square->is_null()) {
      throw new \OutOfBoundsException;
    }
  }

}

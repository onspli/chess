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
        $piece = $this->get_square(new Square($file, $rank));
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

  /**
  * Preview of the board in ASCII graphics.
  */
  public function preview() : string
  {
    $preview = '';
    for ($rank = 7; $rank >= 0; $rank --) {
      for ($file = 0; $file <= 7; $file ++) {
        $piece = $this->get_square(new Square($file, $rank));
        if (!$piece) {
          $piece = '.';
        }
        $preview .= $piece;
      }
      if ($rank != 0) {
        $preview .= "\n";
      }
    }
    return $preview;
  }

  public function get_square($square) : string
  {
    self::validate_square($square);
    return $this->board[$square->get_rank_index() * 8 + $square->get_file_index()];
  }

  public function set_square($square, string $piece) : void
  {
    self::validate_square($square);
    self::validate_piece($piece);
    $this->board[$square->get_rank_index() * 8 + $square->get_file_index()] = $piece;
  }

  /**
  * Get array of all squares attacked (or defended) by $defender being on $defender_square.
  */
  public function get_defended_squares($defender_square, $defender, bool $as_object = false) : array
  {
    self::validate_square($defender_square);
    self::validate_piece($defender);
    $arr = [];

    /**
    * Add square in the direction specified up to the first piece or the end of the board
    */
    $add_direction = function ($north, $east) use (&$arr, $defender_square, $as_object) {
      $this->push_squares_in_direction_to_array($arr, $defender_square, $north, $east, $as_object);
    };

    if ($defender == 'P') {
      $defender_square->get_relative_square(-1,1)->push_to_array($arr, $as_object);
      $defender_square->get_relative_square(1,1)->push_to_array($arr, $as_object);
    } else if ($defender == 'p') {
      $defender_square->get_relative_square(-1,-1)->push_to_array($arr, $as_object);
      $defender_square->get_relative_square(1,-1)->push_to_array($arr, $as_object);
    } else if ($defender == 'K' || $defender == 'k') {
      $defender_square->get_relative_square(0,1)->push_to_array($arr, $as_object);
      $defender_square->get_relative_square(-1,1)->push_to_array($arr, $as_object);
      $defender_square->get_relative_square(-1,0)->push_to_array($arr, $as_object);
      $defender_square->get_relative_square(-1,-1)->push_to_array($arr, $as_object);
      $defender_square->get_relative_square(0,-1)->push_to_array($arr, $as_object);
      $defender_square->get_relative_square(1,-1)->push_to_array($arr, $as_object);
      $defender_square->get_relative_square(1,0)->push_to_array($arr, $as_object);
      $defender_square->get_relative_square(1,1)->push_to_array($arr, $as_object);
    } else if ($defender == 'N' || $defender == 'n') {
      $defender_square->get_relative_square(2, 1)->push_to_array($arr, $as_object);
      $defender_square->get_relative_square(2, -1)->push_to_array($arr, $as_object);
      $defender_square->get_relative_square(-2, 1)->push_to_array($arr, $as_object);
      $defender_square->get_relative_square(-2, -1)->push_to_array($arr, $as_object);
      $defender_square->get_relative_square(1, 2)->push_to_array($arr, $as_object);
      $defender_square->get_relative_square(1, -2)->push_to_array($arr, $as_object);
      $defender_square->get_relative_square(-1, 2)->push_to_array($arr, $as_object);
      $defender_square->get_relative_square(-1, -2)->push_to_array($arr, $as_object);
    } else {
      if ($defender == 'B' || $defender == 'b'  || $defender == 'Q'  || $defender == 'q') {
        $add_direction(1, 1);
        $add_direction(1, -1);
        $add_direction(-1, 1);
        $add_direction(-1, -1);
      }
      if ($defender == 'R' || $defender == 'r'  || $defender == 'Q'  || $defender == 'q') {
        $add_direction(1, 0);
        $add_direction(-1, 0);
        $add_direction(0, 1);
        $add_direction(0, -1);
      }
    }

    return $arr;
  }

  private function push_squares_in_direction_to_array(array &$arr, $origin_square, int $north, int $east, bool $as_object, string $moving_piece = '') {
      $square = $origin_square;
      while ($square = $square->get_relative_square($north, $east)) {
        if (!$this->push_square_to_array($arr, $square, $as_object, $moving_piece)) {
          break;
        }
      }
  }

  private function push_square_to_array(array &$arr, $square, bool $as_object, string $moving_piece = '') : bool
  {
    if ($square->is_null()) {
      return false;
    }
    $target_piece = $this->get_square($square);
    if ($target_piece && $moving_piece && self::get_piece_color($target_piece) == self::get_piece_color($moving_piece)) {
      return false;
    }
    $square->push_to_array($arr, $as_object);
    if ($target_piece != '') {
      return false;
    }
    return true;
  }

  /**
  * Get array of all squares reachable from $origin_square by $moving_piece.
  */
  public function get_reachable_squares($origin_square, $moving_piece, $en_passant_square = '-', bool $as_object = false) : array
  {
    self::validate_square($origin_square);
    self::validate_piece($moving_piece);

    if (is_string($en_passant_square)) {
      $en_passant_square = new Square($en_passant_square);
    }
    $arr = [];

    /**
    * Add square in the direction specified up to the first piece or the end of the board
    */
    $add_direction = function ($north, $east) use (&$arr, $origin_square, $moving_piece, $as_object) {
      $this->push_squares_in_direction_to_array($arr, $origin_square, $north, $east, $as_object, $moving_piece);
    };

    $add_pawn_capture = function($target_square) use (&$arr, $moving_piece, $en_passant_square, $as_object) {
      if ($target_square->is_null()) {
        return;
      }
      if ($target_square->export() == $en_passant_square->export()) {
        $target_square->push_to_array($arr, $as_object);
        return;
      }
      $target_piece = $this->get_square($target_square);
      if (!$target_piece) {
        return;
      }
      if (self::get_piece_color($target_piece) == self::get_piece_color($moving_piece)) {
        return;
      }
      $target_square->push_to_array($arr, $as_object);
    };

    $add_target_square = function($target_square) use (&$arr, $moving_piece, $as_object) {
      $this->push_square_to_array($arr, $target_square, $as_object, $moving_piece);
    };

    if ($moving_piece == 'P') {
      if ($origin_square->get_rank_index() == 1 && $this->get_square($origin_square->get_relative_square(0,1)) == '') {
        $add_target_square($origin_square->get_relative_square(0, 2));
      }
      $add_target_square($origin_square->get_relative_square(0,1));
      $add_pawn_capture($origin_square->get_relative_square(-1,1));
      $add_pawn_capture($origin_square->get_relative_square(1,1));
    } else if ($moving_piece == 'p') {
      if ($origin_square->get_rank_index() == 6 && $this->get_square($origin_square->get_relative_square(0,-1)) == '') {
        $add_target_square($origin_square->get_relative_square(0, -2));
      }
      $add_target_square($origin_square->get_relative_square(0,-1));
      $add_pawn_capture($origin_square->get_relative_square(-1,-1));
      $add_pawn_capture($origin_square->get_relative_square(1,-1));
    } else if ($moving_piece == 'K' || $moving_piece == 'k') {
      $add_target_square($origin_square->get_relative_square(0,1));
      $add_target_square($origin_square->get_relative_square(-1,1));
      $add_target_square($origin_square->get_relative_square(-1,0));
      $add_target_square($origin_square->get_relative_square(-1,-1));
      $add_target_square($origin_square->get_relative_square(0,-1));
      $add_target_square($origin_square->get_relative_square(1,-1));
      $add_target_square($origin_square->get_relative_square(1,0));
      $add_target_square($origin_square->get_relative_square(1,1));
    } else if ($moving_piece == 'N' || $moving_piece == 'n') {
      $add_target_square($origin_square->get_relative_square(2, 1));
      $add_target_square($origin_square->get_relative_square(2, -1));
      $add_target_square($origin_square->get_relative_square(-2, 1));
      $add_target_square($origin_square->get_relative_square(-2, -1));
      $add_target_square($origin_square->get_relative_square(1, 2));
      $add_target_square($origin_square->get_relative_square(1, -2));
      $add_target_square($origin_square->get_relative_square(-1, 2));
      $add_target_square($origin_square->get_relative_square(-1, -2));
    } else {
      if ($moving_piece == 'B' || $moving_piece == 'b'  || $moving_piece == 'Q'  || $moving_piece == 'q') {
        $add_direction(1, 1);
        $add_direction(1, -1);
        $add_direction(-1, 1);
        $add_direction(-1, -1);
      }
      if ($moving_piece == 'R' || $moving_piece == 'r'  || $moving_piece == 'Q'  || $moving_piece == 'q') {
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
  public function get_pieces_on_squares(array $squares) : array
  {
    $arr = [];
    foreach ($squares as $square) {
      $piece = $this->get_square($square);
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
        $p = $this->get_square($square);
        if ($p == $piece) {
          $square->push_to_array($arr, $as_object);
        }
      }
    }
    return $arr;
  }

  public function copy()
  {
    return new self($this->export());
  }

  public static function get_active_piece(string $piece, string $active) : string
  {
    self::validate_active($active);
    self::validate_piece($piece);
    if ($active == 'w') {
      return strtoupper($piece);
    } else {
      return strtolower($piece);
    }
  }

  public static function get_opponents_piece(string $piece, string $active) : string
  {
    self::validate_active($active);
    self::validate_piece($piece);
    if ($active == 'b') {
      return strtoupper($piece);
    } else {
      return strtolower($piece);
    }
  }

  public static function get_piece_color(string $piece) : string
  {
    self::validate_piece($piece);
    if ($piece == self::get_active_piece($piece, 'w')) {
      return 'w';
    } else {
      return 'b';
    }
  }

  /**
  * Returns true if king of active color is in check.
  */
  public function is_check(string $active) : bool
  {
    self::validate_active($active);
    $king_squares = $this->find(self::get_active_piece('K', $active), true);
    if (sizeof($king_squares) != 1) {
      throw new RulesException("There are " . sizeof($king_squares) . " kings on the board.");
    }
    $king_square = $king_squares[0];

    $check_check_from = function ($piece) use ($king_square, $active) {
      $attacker_squares = $this->get_defended_squares($king_square, self::get_active_piece($piece, $active));
      $attackers = $this->get_pieces_on_squares($attacker_squares);
      return in_array(self::get_opponents_piece($piece, $active), $attackers);
    };

    if ($check_check_from('K')) {
      throw new RulesException("Kings are on adjacent squares.");
    }

    if ($check_check_from('P')) {
      return true;
    }

    if ($check_check_from('N')) {
      return true;
    }

    if ($check_check_from('B')) {
      return true;
    }

    if ($check_check_from('R')) {
      return true;
    }

    if ($check_check_from('Q')) {
      return true;
    }
    return false;

  }

  private static function validate_active(string $active) : void
  {
    if (!in_array($active, ['w', 'b'])) {
      throw new ParseException;
    }
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

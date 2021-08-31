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
    $ranks_array = $this->split_to_ranks($pieces);
    foreach ($ranks_array as $index => $rank_pieces)
    {
      $this->fill_rank($rank_pieces, 7 - $index);
    }
  }

  private function split_to_ranks(string $pieces) : array
  {
    $pieces = preg_replace('/\s+/', '', $pieces);
    $ranks_array = explode('/', $pieces);
    if (sizeof($ranks_array) != 8) {
      throw new ParseException("Wrong number of ranks " . sizeof($ranks_array) . ".");
    }
    return $ranks_array;
  }

  private function fill_rank(string $rank_pieces, int $rank_index) : void
  {
    $file_index = 0;
    $rank_pieces_array = str_split($rank_pieces);
    foreach ($rank_pieces_array as $piece)
    {
      if (is_numeric($piece))
      {
        $file_index += intval($piece);
      }
      else
      {
        $this->set_square(new Square($file_index, $rank_index), $piece);
        $file_index += 1;
      }
      if ($file_index > 8) {
        throw new ParseException("Too many pieces on rank.");
      }
    }
  }

  public function export() : string
  {
    $rank_pieces_array = [];
    for ($rank_index = 7; $rank_index >= 0; $rank_index --) {
      $rank_pieces_array[] = $this->export_rank($rank_index);
    }
    return implode('/', $rank_pieces_array);
  }

  private function export_rank(int $rank_index) : string
  {
    $rank_pieces = '';
    $space_count = '';
    for ($file_index = 0; $file_index < 8; $file_index ++) {
      $piece = $this->get_square(new Square($file_index, $rank_index));
      if (!$piece)
      {
        $space_count++;
      }
      if ($piece || $file_index == 7)
      {
        $rank_pieces .= $space_count . $piece;
        $space_count = '';
      }
    }
    return $rank_pieces;
  }

  /**
  * Preview of the board in ASCII graphics.
  */
  public function preview() : string
  {
    $ranks = [];
    $preview = '';
    for ($rank_index = 7; $rank_index >= 0; $rank_index --) {
      $ranks[] = $this->preview_rank($rank_index);
    }
    return implode(PHP_EOL, $ranks);
  }

  private function preview_rank(int $rank_index) : string
  {
    $preview_rank = '';
    for ($file_index = 0; $file_index <= 7; $file_index ++) {
      $piece = $this->get_square(new Square($file_index, $rank_index));
      if (!$piece) {
        $piece = '.';
      }
      $preview_rank .= $piece;
    }
    return $preview_rank;
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


  private function push_squares_in_direction_to_array(array &$arr, $origin_square, int $north, int $east, bool $as_object, string $excluded_color = '') {
      $square = $origin_square;
      while ($square = $square->get_relative_square($north, $east)) {
        if (!$this->push_square_to_array($arr, $square, $as_object, $excluded_color)) {
          break;
        }
      }
  }

  /**
  * Push square to the end of the array.
  *
  * Only pushes regular squares, non-regular squares are ignored.
  * @param string $excluded_color If there is a piece of $excluded_color on the square, the square is NOT pushed to the array.
  * @return bool Returns true if the square was pushed to the array, false otherwise.
  */
  private function push_square_to_array(array &$arr, $square, bool $as_object, string $excluded_color = '') : bool
  {
    if (!$square->is_regular()) {
      return false;
    }
    $target_piece = $this->get_square($square);
    if ($target_piece && $excluded_color && self::get_piece_color($target_piece) == $excluded_color) {
      return false;
    }
    $square->push_to_array($arr, $as_object);
    if ($target_piece != '') {
      return false;
    }
    return true;
  }

  private function push_squares_defended_by_king_to_array(array &$arr, $king_square, bool $as_object, string $excluded_color = '')
  {
    $this->push_square_to_array($arr, $king_square->get_relative_square(0,1), $as_object, $excluded_color);
    $this->push_square_to_array($arr, $king_square->get_relative_square(-1,1), $as_object, $excluded_color);
    $this->push_square_to_array($arr, $king_square->get_relative_square(-1,0), $as_object, $excluded_color);
    $this->push_square_to_array($arr, $king_square->get_relative_square(-1,-1), $as_object, $excluded_color);
    $this->push_square_to_array($arr, $king_square->get_relative_square(0,-1), $as_object, $excluded_color);
    $this->push_square_to_array($arr, $king_square->get_relative_square(1,-1), $as_object, $excluded_color);
    $this->push_square_to_array($arr, $king_square->get_relative_square(1,0), $as_object, $excluded_color);
    $this->push_square_to_array($arr, $king_square->get_relative_square(1,1), $as_object, $excluded_color);
  }

  private function push_squares_defended_by_knight_to_array(array &$arr, $knight_square, bool $as_object, string $excluded_color = '')
  {
    $this->push_square_to_array($arr, $knight_square->get_relative_square(2, 1), $as_object, $excluded_color);
    $this->push_square_to_array($arr, $knight_square->get_relative_square(2, -1), $as_object, $excluded_color);
    $this->push_square_to_array($arr, $knight_square->get_relative_square(-2, 1), $as_object, $excluded_color);
    $this->push_square_to_array($arr, $knight_square->get_relative_square(-2, -1), $as_object, $excluded_color);
    $this->push_square_to_array($arr, $knight_square->get_relative_square(1, 2), $as_object, $excluded_color);
    $this->push_square_to_array($arr, $knight_square->get_relative_square(1, -2), $as_object, $excluded_color);
    $this->push_square_to_array($arr, $knight_square->get_relative_square(-1, 2), $as_object, $excluded_color);
    $this->push_square_to_array($arr, $knight_square->get_relative_square(-1, -2), $as_object, $excluded_color);
  }

  private function push_squares_defended_by_bishop_to_array(array &$arr, $bishop_square, bool $as_object, string $excluded_color = '')
  {
    $this->push_squares_in_direction_to_array($arr, $bishop_square, 1, 1, $as_object, $excluded_color);
    $this->push_squares_in_direction_to_array($arr, $bishop_square, -1, 1, $as_object, $excluded_color);
    $this->push_squares_in_direction_to_array($arr, $bishop_square, 1, -1, $as_object, $excluded_color);
    $this->push_squares_in_direction_to_array($arr, $bishop_square, -1, -1, $as_object, $excluded_color);
  }

  private function push_squares_defended_by_rook_to_array(array &$arr, $rook_square, bool $as_object, string $excluded_color = '')
  {
    $this->push_squares_in_direction_to_array($arr, $rook_square, 1, 0, $as_object, $excluded_color);
    $this->push_squares_in_direction_to_array($arr, $rook_square, -1, 0, $as_object, $excluded_color);
    $this->push_squares_in_direction_to_array($arr, $rook_square, 0, 1, $as_object, $excluded_color);
    $this->push_squares_in_direction_to_array($arr, $rook_square, 0, -1, $as_object, $excluded_color);
  }

  private function push_squares_defended_by_queen_to_array(array &$arr, $queen_square, bool $as_object, string $excluded_color = '')
  {
    $this->push_squares_defended_by_rook_to_array($arr, $queen_square, $as_object, $excluded_color);
    $this->push_squares_defended_by_bishop_to_array($arr, $queen_square, $as_object, $excluded_color);
  }

  private function push_squares_defended_by_not_a_pawn_to_array(array &$arr, string $piece, $piece_square, bool $as_object, string $excluded_color = '')
  {
    switch ($piece) {
      case 'K':
      case 'k':
        $this->push_squares_defended_by_king_to_array($arr, $piece_square, $as_object, $excluded_color);
        break;
      case 'N':
      case 'n':
        $this->push_squares_defended_by_knight_to_array($arr, $piece_square, $as_object, $excluded_color);
        break;
      case 'B':
      case 'b':
        $this->push_squares_defended_by_bishop_to_array($arr, $piece_square, $as_object, $excluded_color);
        break;
      case 'R':
      case 'r':
        $this->push_squares_defended_by_rook_to_array($arr, $piece_square, $as_object, $excluded_color);
        break;
      case 'Q':
      case 'q':
        $this->push_squares_defended_by_queen_to_array($arr, $piece_square, $as_object, $excluded_color);
        break;
    }
  }

  /**
  * Get array of all squares defended (or attacked) by $defender being on $defender_square.
  */
  public function get_defended_squares($defender_square, $defender, bool $as_object = false) : array
  {
    self::validate_square($defender_square);
    self::validate_piece($defender);
    $arr = [];

    $add_target_square = function($target_square) use (&$arr, $as_object) {
      $this->push_square_to_array($arr, $target_square, $as_object);
    };

    if ($defender == 'P') {
      $add_target_square($defender_square->get_relative_square(-1,1));
      $add_target_square($defender_square->get_relative_square(1,1));
    } else if ($defender == 'p') {
      $add_target_square($defender_square->get_relative_square(-1,-1));
      $add_target_square($defender_square->get_relative_square(1,-1));
    } else {
      $this->push_squares_defended_by_not_a_pawn_to_array($arr, $defender, $defender_square, $as_object);
    }

    return $arr;
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
      $this->push_square_to_array($arr, $target_square, $as_object, self::get_piece_color($moving_piece));
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
    } else {
      $this->push_squares_defended_by_not_a_pawn_to_array($arr, $moving_piece, $origin_square, $as_object, self::get_piece_color($moving_piece));
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
    if (!$square->is_regular()) {
      throw new \OutOfBoundsException;
    }
  }

}

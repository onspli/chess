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
    self::validate_regular_square($square);
    return $this->board[$square->get_rank_index() * 8 + $square->get_file_index()];
  }

  public function set_square($square, string $piece) : void
  {
    self::validate_regular_square($square);
    self::validate_piece($piece);
    $this->board[$square->get_rank_index() * 8 + $square->get_file_index()] = $piece;
  }


  private function push_squares_in_direction_to_array(array &$arr, Square $origin_square, int $north, int $east, bool $as_object, string $excluded_color = '') : void
  {
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
  * @return bool Returns false if the square is not regular or if it contains a piece.
  */
  private function push_square_to_array(array &$arr, Square $square, bool $as_object, string $excluded_color = '') : bool
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

  private function push_squares_defended_by_king_to_array(array &$arr, Square $king_square, bool $as_object, string $excluded_color = '') : void
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

  private function push_squares_defended_by_knight_to_array(array &$arr, Square $knight_square, bool $as_object, string $excluded_color = '') : void
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

  private function push_squares_defended_by_bishop_to_array(array &$arr, Square $bishop_square, bool $as_object, string $excluded_color = '') : void
  {
    $this->push_squares_in_direction_to_array($arr, $bishop_square, 1, 1, $as_object, $excluded_color);
    $this->push_squares_in_direction_to_array($arr, $bishop_square, -1, 1, $as_object, $excluded_color);
    $this->push_squares_in_direction_to_array($arr, $bishop_square, 1, -1, $as_object, $excluded_color);
    $this->push_squares_in_direction_to_array($arr, $bishop_square, -1, -1, $as_object, $excluded_color);
  }

  private function push_squares_defended_by_rook_to_array(array &$arr, Square $rook_square, bool $as_object, string $excluded_color = '') : void
  {
    $this->push_squares_in_direction_to_array($arr, $rook_square, 1, 0, $as_object, $excluded_color);
    $this->push_squares_in_direction_to_array($arr, $rook_square, -1, 0, $as_object, $excluded_color);
    $this->push_squares_in_direction_to_array($arr, $rook_square, 0, 1, $as_object, $excluded_color);
    $this->push_squares_in_direction_to_array($arr, $rook_square, 0, -1, $as_object, $excluded_color);
  }

  private function push_squares_defended_by_queen_to_array(array &$arr, Square $queen_square, bool $as_object, string $excluded_color = '') : void
  {
    $this->push_squares_defended_by_rook_to_array($arr, $queen_square, $as_object, $excluded_color);
    $this->push_squares_defended_by_bishop_to_array($arr, $queen_square, $as_object, $excluded_color);
  }

  private function push_squares_defended_by_not_a_pawn_to_array(array &$arr, string $piece, Square $piece_square, bool $as_object, string $excluded_color = '') : void
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

  private function push_square_with_pawn_capture_to_array(array &$arr, Square $target_square, Square $en_passant_square, bool $as_object, string $excluded_color) : void
  {
    if (!$target_square->is_regular()) {
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

    if ($excluded_color && self::get_piece_color($target_piece) == $excluded_color) {
      return;
    }
    $target_square->push_to_array($arr, $as_object);
  }

  private function push_squares_reachable_by_white_pawn_to_array(array &$arr, Square $pawn_square, Square $en_passant_square, bool $as_object) : void
  {
    if ($pawn_square->get_rank_index() == 1 && $this->get_square($pawn_square->get_relative_square(0,1)) == '') {
      $this->push_square_to_array($arr, $pawn_square->get_relative_square(0, 2), $as_object, 'w');
    }
    $this->push_square_to_array($arr, $pawn_square->get_relative_square(0, 1), $as_object, 'w');
    $this->push_square_with_pawn_capture_to_array($arr, $pawn_square->get_relative_square(-1, 1), $en_passant_square, $as_object, 'w');
    $this->push_square_with_pawn_capture_to_array($arr, $pawn_square->get_relative_square(1, 1), $en_passant_square, $as_object, 'w');
  }

  private function push_squares_reachable_by_black_pawn_to_array(array &$arr, Square $pawn_square, Square $en_passant_square, bool $as_object) : void
  {
    if ($pawn_square->get_rank_index() == 6 && $this->get_square($pawn_square->get_relative_square(0,-1)) == '') {
      $this->push_square_to_array($arr, $pawn_square->get_relative_square(0, -2), $as_object, 'b');
    }
    $this->push_square_to_array($arr, $pawn_square->get_relative_square(0, -1), $as_object, 'b');
    $this->push_square_with_pawn_capture_to_array($arr, $pawn_square->get_relative_square(-1, -1), $en_passant_square, $as_object, 'b');
    $this->push_square_with_pawn_capture_to_array($arr, $pawn_square->get_relative_square(1, -1), $en_passant_square, $as_object, 'b');
  }

  /**
  * Get array of all squares defended (or attacked) by $defender being on $defender_square.
  */
  public function get_defended_squares($defender_square, string $defender, bool $as_object = false) : array
  {
    self::validate_regular_square($defender_square);
    self::validate_regular_piece($defender);
    $arr = [];

    switch ($defender) {
      case 'P':
        $this->push_square_to_array($arr, $defender_square->get_relative_square(-1, 1), $as_object);
        $this->push_square_to_array($arr, $defender_square->get_relative_square(1, 1), $as_object);
        break;
      case 'p':
        $this->push_square_to_array($arr, $defender_square->get_relative_square(-1, -1), $as_object);
        $this->push_square_to_array($arr, $defender_square->get_relative_square(1, -1), $as_object);
        break;
      default:
        $this->push_squares_defended_by_not_a_pawn_to_array($arr, $defender, $defender_square, $as_object);
    }
    return $arr;
  }

  /**
  * Get array of all squares reachable from $origin_square by $moving_piece.
  */
  public function get_reachable_squares($origin_square, $moving_piece, $en_passant_square = '-', bool $as_object = false) : array
  {
    self::validate_regular_square($origin_square);
    self::validate_regular_piece($moving_piece);
    self::validate_square($en_passant_square);
    $arr = [];

    switch ($moving_piece) {
      case 'P':
        $this->push_squares_reachable_by_white_pawn_to_array($arr, $origin_square, $en_passant_square, $as_object);
        break;
      case 'p':
        $this->push_squares_reachable_by_black_pawn_to_array($arr, $origin_square, $en_passant_square, $as_object);
        break;
      default:
        $this->push_squares_defended_by_not_a_pawn_to_array($arr, $moving_piece, $origin_square, $as_object, self::get_piece_color($moving_piece));
    }

    return $arr;
  }

  /**
  * Get list of pieces on squares (including multiplicities, excluding blank squares).
  */
  private function get_pieces_on_squares(array $squares) : array
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
  private function find_squares_with_piece(string $piece, bool $as_object = false) : array
  {
    self::validate_piece($piece);
    $arr = [];
    for ($rank_index = 0; $rank_index < 8; $rank_index ++) {
      for ($file_index = 0; $file_index < 8; $file_index ++) {
        $square = new Square($file_index, $rank_index);
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

  public static function get_active_piece(string $piece, string $active_color) : string
  {
    self::validate_color($active_color);
    self::validate_piece($piece);
    if ($active_color == 'w') {
      return strtoupper($piece);
    } else {
      return strtolower($piece);
    }
  }

  public static function get_opponents_piece(string $piece, string $active_color) : string
  {
    self::validate_color($active_color);
    self::validate_piece($piece);
    if ($active_color == 'b') {
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

  private static function get_opponents_color(string $color) : string
  {
    if ($color == 'b') {
      return 'w';
    } else {
      return 'b';
    }
  }

  public function is_square_attacked_by_piece($square, string $piece) : bool
  {
    self::validate_regular_square($square);
    self::validate_regular_piece($piece);

    $shadow_piece = $piece;
    if ($piece == 'p' || $piece == 'P') {
      $shadow_piece = self::get_opponents_piece($piece, self::get_piece_color($piece));
    }

    $candidate_squares = $this->get_defended_squares($square, $shadow_piece);
    $attackers = $this->get_pieces_on_squares($candidate_squares);
    return in_array($piece, $attackers);
  }

  public function is_square_attacked($square, string $attacking_color) : bool
  {
    self::validate_regular_square($square);
    self::validate_color($attacking_color);

    if ($this->is_square_attacked_by_piece($square, self::get_active_piece('P', $attacking_color))) {
      return true;
    }

    if ($this->is_square_attacked_by_piece($square, self::get_active_piece('N', $attacking_color))) {
      return true;
    }

    if ($this->is_square_attacked_by_piece($square, self::get_active_piece('B', $attacking_color))) {
      return true;
    }

    if ($this->is_square_attacked_by_piece($square, self::get_active_piece('R', $attacking_color))) {
      return true;
    }

    if ($this->is_square_attacked_by_piece($square, self::get_active_piece('Q', $attacking_color))) {
      return true;
    }

    if ($this->is_square_attacked_by_piece($square, self::get_active_piece('K', $attacking_color))) {
      return true;
    }

    return false;
  }

  public function is_check(string $active_color) : bool
  {
    self::validate_color($active_color);

    $king_squares = $this->find_squares_with_piece(self::get_active_piece('K', $active_color), true);
    if (sizeof($king_squares) != 1) {
      throw new RulesException("There are " . sizeof($king_squares) . " kings of active color on the board.");
    }

    return $this->is_square_attacked($king_squares[0], self::get_opponents_color($active_color));
  }

  private static function validate_color(string $color) : void
  {
    if (!in_array($color, ['w', 'b'])) {
      throw new ParseException;
    }
  }

  private static function validate_piece(string $piece) : void
  {
    if (!in_array($piece, ['', 'P', 'N', 'B', 'R', 'Q', 'K', 'p', 'n', 'b', 'r', 'q', 'k'])) {
      throw new ParseException("Invalid piece '$piece'.");
    }
  }

  private static function validate_regular_piece(string $piece) : void
  {
    if (!in_array($piece, ['P', 'N', 'B', 'R', 'Q', 'K', 'p', 'n', 'b', 'r', 'q', 'k'])) {
      throw new ParseException("Invalid piece '$piece'.");
    }
  }

  private static function validate_square(&$square) : void
  {
    if (is_string($square)) {
      $square = new Square($square);
    }
  }

  private static function validate_regular_square(&$square) : void
  {
    self::validate_square($square);
    if (!$square->is_regular()) {
      throw new \OutOfBoundsException;
    }
  }

}

<?php

namespace Onspli\Chess;

/**
* FEN is a standard notation for describing a particular board position of a chess game
*
* Forsyth–Edwards Notation (FEN) is a standard notation for describing a particular board
* position of a chess game. The purpose of FEN is to provide all the necessary information
* to restart a game from a particular position.
*
* Class provides intefrace for reading and setting all FEN fields, and also
* method for checking game state (check, mate, stalemate, fifty-move rule draw),
* getting all available moves in the position and changing the position by performing
* move according to chess rules.
*/
class FEN
{
    private $board;
    private $active = 'w';
    private $castling = 'KQkq';
    private $en_passant;
    private $halfmove = 0;
    private $fullmove = 1;

    /**
    * Load FEN or setup starting position.
    * @param string $fen
    * @return void
    */
    function __construct(string $fen = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')
    {
      $fen = trim($fen);
      $fen = preg_replace('/\s+/', ' ', $fen);
      $parts = explode(' ', $fen);
      if (sizeof($parts) != 6) {
        throw new ParseException("FEN has " . sizeof($parts) . " fields. It must have 6 fields.");
      }

      $this->set_board($parts[0]);
      $this->set_active_color($parts[1]);
      $this->set_castling_string($parts[2]);
      $this->set_en_passant($parts[3]);
      $this->set_halfmove($parts[4]);
      $this->set_fullmove($parts[5]);
    }

    /**
    * Creates deep copy of the FEN instance.
    */
    public function copy()
    {
      return new self($this->export());
    }

    /**
    * Export whole FEN string.
    * @return string FEN string
    */
    public function export() : string
    {
      return implode(' ', [$this->get_board(), $this->get_active_color(), $this->get_castling_string(), $this->get_en_passant(), $this->get_halfmove(), $this->get_fullmove()]);
    }

    public function __toString() : string
    {
      return $this->export();
    }

    /**
    * Preview of the board in ASCII graphics.
    * @return string ascii preview of the board
    */
    public function preview() : string
    {
      return $this->board->preview();
    }

    /**
    * Get piece placement.
    *
    * Piece placement (from White's perspective). Each rank is described,
    * starting with rank 8 and ending with rank 1; within each rank,
    * the contents of each square are described from file "a" through file "h".
    * Following the Standard Algebraic Notation (SAN), each piece is identified
    * by a single letter taken from the standard English names (pawn = "P",
    * knight = "N", bishop = "B", rook = "R", queen = "Q" and king = "K").
    * White pieces are designated using upper-case letters ("PNBRQK") while
    * black pieces use lowercase ("pnbrqk"). Empty squares are noted using
    * digits 1 through 8 (the number of empty squares), and "/" separates ranks.
    *
    * @param bool $as_object return object instead of string
    * @return string|Board
    */
    public function get_board(bool $as_object = false)
    {
      if ($as_object) {
        return $this->board;
      }
      return $this->board->export();
    }

    /**
    * Setup piece placement.
    *
    * Piece placement (from White's perspective). Each rank is described,
    * starting with rank 8 and ending with rank 1; within each rank,
    * the contents of each square are described from file "a" through file "h".
    * Following the Standard Algebraic Notation (SAN), each piece is identified
    * by a single letter taken from the standard English names (pawn = "P",
    * knight = "N", bishop = "B", rook = "R", queen = "Q" and king = "K").
    * White pieces are designated using upper-case letters ("PNBRQK") while
    * black pieces use lowercase ("pnbrqk"). Empty squares are noted using
    * digits 1 through 8 (the number of empty squares), and "/" separates ranks.
    *
    * @param string|Board $board piece placement
    */
    public function set_board($board) : void
    {
      if (is_string($board)) {
        $board = new Board($board);
      }
      $this->board = $board;
    }

    /**
    * Get piece on a particular square.
    *
    * @param string|Square square on the board.
    * @return string piece - one of PNBRQKpnbrqk or empty string for empty square
    */
    public function get_square($square) : string
    {
      return $this->board->get_square($square);
    }

    /**
    * Set piece on a particular square.
    *
    * @param string|Square square on the board.
    * @param string piece - one of PNBRQKpnbrqk or empty string for empty square
    */
    public function set_square($square, string $piece) : void
    {
      $this->board->set_square($square, $piece);
    }

    /**
    * Active color.
    *
    * "w" means White moves next, "b" means Black moves next.
    *
    * @return string w|b
    */
    public function get_active_color() : string
    {
      return $this->active;
    }

    /**
    * Set active color.
    *
    * "w" means White moves next, "b" means Black moves next.
    *
    * @param string $color w|b
    */
    public function set_active_color(string $color) : void
    {
      if ($color != 'w' && $color != 'b') {
        throw new ParseException("Active color must be either 'w' or 'b', it is '$color'.");
      }
      $this->active = $color;
    }

    /**
    * Castling availability.
    *
    * If neither side can castle, this is "-".
    * Otherwise, this has one or more letters: "K" (White can castle kingside),
    * "Q" (White can castle queenside), "k" (Black can castle kingside), and/or
    * "q" (Black can castle queenside). A move that temporarily prevents castling
    * does not negate this notation.
    *
    * @return string castling availability string
    */
    public function get_castling_string() : string
    {
      return $this->castling;
    }

    /**
    * Set castling availability.
    *
    * If neither side can castle, this is "-".
    * Otherwise, this has one or more letters: "K" (White can castle kingside),
    * "Q" (White can castle queenside), "k" (Black can castle kingside), and/or
    * "q" (Black can castle queenside). A move that temporarily prevents castling
    * does not negate this notation.
    *
    * @param string castling availability string
    */
    public function set_castling_string(string $castling) : void
    {
      if (!in_array($castling, ['-', 'KQkq', 'KQk', 'KQq', 'KQ', 'Kkq', 'Kk', 'Kq', 'K', 'Qkq', 'Qk', 'Qq', 'Q', 'kq', 'k', 'q'])) {
        throw new ParseException("Invalid castling string '$castling'.");
      }
      $this->castling = $castling;
    }

    /**
    * Get castling availability of particular type.
    *
    * Possible castling types: "K" (White can castle kingside),
    * "Q" (White can castle queenside), "k" (Black can castle kingside), and/or
    * "q" (Black can castle queenside). A move that temporarily prevents castling
    * does not negate this notation.
    *
    * @param string castling type
    */
    public function get_castling_availability(string $type) : bool
    {
      self::validate_castling_type($type);
      $castling = str_split($this->castling);
      return in_array($type, $castling);
    }

    /**
    * Set castling availability of particular type.
    *
    * Possible castling types: "K" (White can castle kingside),
    * "Q" (White can castle queenside), "k" (Black can castle kingside), and/or
    * "q" (Black can castle queenside). A move that temporarily prevents castling
    * does not negate this notation.
    *
    * @param string castling type
    * @param bool set availability
    */
    public function set_castling_availability(string $type, bool $avalability) : void
    {
      self::validate_castling_type($type);
      if ($this->get_castling_availability($type) === $avalability) {
        return;
      }

      // convert str to array of available types
      if ($this->castling == '-') {
        $castling = [];
      }
      else {
        $castling = str_split($this->castling);
      }

      // add or remove castling availability for type
      if ($avalability === false) {
        $castling = array_diff($castling, [$type]);
      }
      else {
        $castling = array_merge($castling, [$type]);
      }

      // sort and convert array back to string
      sort($castling);
      $castling = sizeof($castling) ? implode('', $castling) : '-';

      $this->set_castling_string($castling);
    }

    private static function validate_castling_type(string $type) : void
    {
      if (!in_array($type, ['K', 'Q', 'k', 'q'])) {
        throw new ParseException("Invalid castling type '$type'.");
      }
    }

    /**
    * Get En Passant target square.
    *
    * En passant target square in algebraic notation. If there's no en passant
    * target square, this is "-". If a pawn has just made a two-square move,
    * this is the position "behind" the pawn. This is recorded regardless of
    * whether there is a pawn in position to make an en passant capture.
    *
    * @param bool $as_object return object instead of string
    * @return string|Square
    */
    public function get_en_passant(bool $as_object = false)
    {
      if ($as_object) {
        return $this->en_passant;
      }
      return $this->en_passant->export();
    }

    /**
    * Set En Passant target square.
    *
    * En passant target square in algebraic notation. If there's no en passant
    * target square, this is "-". If a pawn has just made a two-square move,
    * this is the position "behind" the pawn. This is recorded regardless of
    * whether there is a pawn in position to make an en passant capture.
    *
    * @param string|Square en passant target square
    */
    public function set_en_passant($square) : void
    {
      if (is_string($square)) {
        $square = new Square($square);
      }
      if ($square->is_null() == false && $square->get_rank_index() != 2 && $square->get_rank_index() != 5) {
        throw new ParseException("Invalid En passant square '".$square->export()."'.");
      }
      $this->en_passant = $square;
    }

    /**
    * Get Halfmove clock
    *
    * The number of halfmoves since the last capture or pawn
    * advance, used for the fifty-move rule.
    */
    public function get_halfmove() : int
    {
      return $this->halfmove;
    }

    /**
    * Set Halfmove clock
    *
    * The number of halfmoves since the last capture or pawn
    * advance, used for the fifty-move rule.
    */
    public function set_halfmove($halfmove) : void
    {
      if (intval($halfmove) != $halfmove || $halfmove < 0) {
        throw new ParseException("Halfmove clock '$halfmove' must be non-negative integer.");
      }
      $this->halfmove = $halfmove;
    }

    /**
    * Get Fullmove number
    *
    * The number of the full move. It starts at 1, and is
    * incremented after Black's move.
    */
    public function get_fullmove() : int
    {
      return $this->fullmove;
    }

    /**
    * Set Fullmove number
    *
    * The number of the full move. It starts at 1, and is
    * incremented after Black's move.
    */
    public function set_fullmove($fullmove) : void
    {
      if (intval($fullmove) != $fullmove || $fullmove <= 0) {
        throw new ParseException("Fullmove number '$fullmove' must be positive integer.");
      }
      $this->fullmove = $fullmove;
    }

    /**
    * Returns true if king of active color is in mate.
    */
    public function is_mate() : bool
    {
      return $this->is_check() && sizeof($this->get_legal_moves()) == 0;
    }

    /**
    * Returns true if king of active color is in stalemate.
    */
    public function is_stalemate() : bool
    {
      return !$this->is_check() && sizeof($this->get_legal_moves()) == 0;
    }

    /**
    * Returns true if fifty move rule draw can be claimed by active color.
    */
    public function is_fifty_move() : bool
    {
      return $this->get_halfmove() >= 100;
    }

    /**
    * Returns true if king of active color is in check.
    */
    public function is_check() : bool
    {
      return $this->board->is_check($this->get_active_color());
    }

    private function get_active_piece(string $piece) : string
    {
      return Board::get_piece_of_color($piece, $this->get_active_color());
    }

    private function get_opponents_piece(string $piece) : string
    {
      return Board::get_piece_of_color($piece, Board::get_opposite_color($this->get_active_color()));
    }

    private function get_reachable_squares_origins(string $piece) : array
    {
      $reachable_squares_origins = [];
      $origin_candidates = $this->board->find_squares_with_piece($piece, true);
      foreach ($origin_candidates as $origin_square) {
        $reachable_squares = $this->board->get_reachable_squares($origin_square, $piece);
        foreach ($reachable_squares as $reachable_square) {
          if (!array_key_exists($reachable_square, $reachable_squares_origins)) {
            $reachable_squares_origins[$reachable_square] = [];
          }
          $reachable_squares_origins[$reachable_square][] = $origin_square;
        }
      }
      return $reachable_squares_origins;
    }

    private function push_pieces_pseudolegal_moves_to_array(array &$arr, string $piece) : void
    {
      $reachable_squares_origins = $this->get_reachable_squares_origins($piece);

      foreach ($reachable_squares_origins as $target_square_str => $origin_squares) {
        $this->push_piece_pseudolegal_moves_to_specific_target_square_to_array($arr, $piece, $origin_squares, new Square($target_square_str));
      }
    }

    private function push_piece_pseudolegal_moves_to_specific_target_square_to_array(array &$arr, string $piece, array $origin_squares, Square $target_square) : void
    {

      $capture = $this->is_capture($target_square) ? 'x' : '';
      $piece_type = Board::get_piece_of_color($piece, 'w');

      if (sizeof($origin_squares) == 1) {
        $arr[] = $piece_type . $capture . $target_square;
      } else if (sizeof($origin_squares) == 2) {
        if ($origin_squares[0]->get_file_index() != $origin_squares[1]->get_file_index()) {
          $arr[] = $piece_type . $origin_squares[0]->get_file() . $capture . $target_square;
          $arr[] = $piece_type . $origin_squares[1]->get_file() . $capture . $target_square;
        } else {
          $arr[] = $piece_type . $origin_squares[0]->get_rank() . $capture . $target_square;
          $arr[] = $piece_type . $origin_squares[1]->get_rank() . $capture . $target_square;
        }
      } else {
        foreach ($origin_squares as $origin_square) {
          $arr[] = $piece_type . $origin_square . $capture . $target_square;
        }
      }
    }

    /**
    * Add possible moves for all pawns of specified color.
    */
    private function push_pawns_pseudolegal_moves_to_array(array &$arr, string $color) : void
    {
      $piece = Board::get_piece_of_color('P', $color);
      $origin_squares = $this->board->find_squares_with_piece($piece, true);
      foreach ($origin_squares as $origin_square) {
        $target_squares = $this->board->get_reachable_squares($origin_square, $piece, $this->get_en_passant(), true);
        foreach ($target_squares as $target_square) {
          $this->push_pawn_pseudolegal_move_to_array($arr, $origin_square, $target_square);
        }
      }
    }

    /**
    * Add pawn's moves from specified origin to specified target including all possible promotions.
    */
    private function push_pawn_pseudolegal_move_to_array(array &$arr, Square $origin_square, Square $target_square) : void
    {
      $promotions = $this->get_promotion_strings($target_square);
      foreach ($promotions as $promotion) {
        if ($this->is_capture($target_square)) {
          $arr[] = $origin_square->get_file() . 'x' . $target_square . $promotion;
        } else {
          $arr[] = $target_square . $promotion;
        }
      }
    }

    private function get_promotion_strings(Square $target_square) : array
    {
      if (self::is_promoting_square($target_square, $this->get_active_color())) {
        return ['=N', '=B', '=R', '=Q'];
      } else {
        return [''];
      }
    }

    private function is_capture(Square $target_square) : bool
    {
      return $this->get_square($target_square) != '';
    }

    static private function is_promoting_square(Square $target_square) : bool
    {
      $rank_index = $target_square->get_rank_index();
      return $rank_index == 7 || $rank_index == 0;
    }

    /**
    * Array of all possible moves in current position.
    */
    public function get_legal_moves() : array
    {
      $pseudolegal_moves = [];

      $this->push_pawns_pseudolegal_moves_to_array($pseudolegal_moves, $this->get_active_color());
      $this->push_pieces_pseudolegal_moves_to_array($pseudolegal_moves, $this->get_active_piece('N'));
      $this->push_pieces_pseudolegal_moves_to_array($pseudolegal_moves, $this->get_active_piece('B'));
      $this->push_pieces_pseudolegal_moves_to_array($pseudolegal_moves, $this->get_active_piece('R'));
      $this->push_pieces_pseudolegal_moves_to_array($pseudolegal_moves, $this->get_active_piece('Q'));
      $this->push_pieces_pseudolegal_moves_to_array($pseudolegal_moves, $this->get_active_piece('K'));

      if ($this->get_castling_availability($this->get_active_piece('Q'))) {
        $pseudolegal_moves[] = 'O-O-O';
      }
      if ($this->get_castling_availability($this->get_active_piece('K'))) {
        $pseudolegal_moves[] = 'O-O';
      }

      return array_filter($pseudolegal_moves, [$this, 'is_legal_move']);
    }

    /**
    * Tests if move is legal.
    */
    public function is_legal_move(string $move) : bool
    {
      $fen = $this->copy();
      try {
        $fen->move($move);
      } catch (\Exception $e) {
        return false;
      };
      return true;
    }

    private function castle_kingside(Move &$move) : void
    {
      if (!$this->get_castling_availability($this->get_active_piece('K'))) {
        throw new RulesException("Castling not available.");
      }

      if ($this->get_active_color() == 'w') {
        $origin = new Square('e1');
      } else {
        $origin = new Square('e8');
      }

      if ($this->get_square($origin) != $this->get_active_piece('K')) {
        throw new RulesException("Castling not available. King not in initial position.");
      }
      if ($this->get_square($origin->get_relative_square(1, 0)) || $this->get_square($origin->get_relative_square(2, 0))) {
        throw new RulesException("Castling not available. There are pieces in the way.");
      }
      if ($this->get_square($origin->get_relative_square(3, 0)) != $this->get_active_piece('R')) {
        throw new RulesException("Castling not available. Rook not in initial position");
      }
      if ($this->is_check()) {
        throw new RulesException("Castling not available. King in check.");
      }

      $new_board = $this->board->copy();
      $new_board->set_square($origin, '');
      $new_board->set_square($origin->get_relative_square(1, 0), $this->get_active_piece('K'));
      if ($new_board->is_check($this->get_active_color())) {
        throw new RulesException("Castling not available. King in check.");
      }

      $new_board->set_square($origin->get_relative_square(1, 0), $this->get_active_piece('R'));
      $new_board->set_square($origin->get_relative_square(3, 0), '');
      $new_board->set_square($origin->get_relative_square(2, 0), $this->get_active_piece('K'));

      $this->set_new_board($new_board);
      $move->set_origin($origin);
    }

    private function castle_queenside(Move &$move) : void
    {
      if (!$this->get_castling_availability($this->get_active_piece('Q'))) {
        throw new RulesException("Castling not available.");
      }

      if ($this->get_active_color() == 'w') {
        $origin = new Square('e1');
      } else {
        $origin = new Square('e8');
      }

      if ($this->get_square($origin) != $this->get_active_piece('K')) {
        throw new RulesException("Castling not available. King not in initial position.");
      }
      if ($this->get_square($origin->get_relative_square(-1, 0)) || $this->get_square($origin->get_relative_square(-2, 0)) || $this->get_square($origin->get_relative_square(-3, 0))) {
        throw new RulesException("Castling not available. There are pieces in the way.");
      }
      if ($this->get_square($origin->get_relative_square(-4, 0)) != $this->get_active_piece('R')) {
        throw new RulesException("Castling not available. Rook not in initial position");
      }
      if ($this->is_check()) {
        throw new RulesException("Castling not available. King in check.");
      }

      $new_board = $this->board->copy();
      $new_board->set_square($origin, '');
      $new_board->set_square($origin->get_relative_square(-1, 0), $this->get_active_piece('K'));
      if ($new_board->is_check($this->get_active_color())) {
        throw new RulesException("Castling not available. King in check.");
      }

      $new_board->set_square($origin->get_relative_square(-1, 0), $this->get_active_piece('R'));
      $new_board->set_square($origin->get_relative_square(-4, 0), '');
      $new_board->set_square($origin->get_relative_square(-2, 0), $this->get_active_piece('K'));

      $this->set_new_board($new_board);
      $move->set_origin($origin);
    }

    private function get_move_origin_candidates(Move $move) : array
    {
      $move_piece = $this->get_active_piece($move->get_piece());
      $target = $move->get_target(true);
      $target_piece = $this->get_square($target);

      if ($move_piece == 'P' && !$move->get_capture()) {
        $origin_candidates = [$target->get_relative_square(0, -1)];
        if ($target->get_rank_index() == 3 && $this->get_square($target->get_relative_square(0, -1)) == '') {
          $origin_candidates[] = $target->get_relative_square(0, -2);
        }
      } else if($move_piece == 'p' && !$move->get_capture()) {
        $origin_candidates = [$target->get_relative_square(0, 1)];
        if ($target->get_rank_index() == 4 && $this->get_square($target->get_relative_square(0, 1)) == '') {
          $origin_candidates[] = $target->get_relative_square(0, 2);
        }
      } else {
        $origin_candidates = $this->board->get_defended_squares($target, $this->get_opponents_piece($move_piece), true);
      }
      return $origin_candidates;
    }

    private function get_move_origin(Move $move, array $origin_candidates) : Square
    {
      $move_piece = $this->get_active_piece($move->get_piece());
      $filtered_origin_candidates = [];
      foreach ($origin_candidates as $origin_candidate) {
        if ($this->get_square($origin_candidate) == $move_piece) {
          if ($move->get_origin(true)->has_file() && $origin_candidate->get_file() != $move->get_origin(true)->get_file()) {
            continue;
          }
          if ($move->get_origin(true)->has_rank() && $origin_candidate->get_rank() != $move->get_origin(true)->get_rank()) {
            continue;
          }
          $filtered_origin_candidates[] = $origin_candidate;
        }
      }

      if (sizeof($filtered_origin_candidates) == 0) {
        throw new RulesException("Invalid move.");
      }

      if (sizeof($filtered_origin_candidates) > 1) {
        throw new RulesException("Ambiguous move.");
      }

      return $filtered_origin_candidates[0];
    }

    private function validate_capture_move(Move $move) : void
    {
      $move_piece = $this->get_active_piece($move->get_piece());
      $target = $move->get_target(true);
      $target_piece = $this->get_square($target);

      if ($move->get_capture() && $target_piece == '' && !($target->export() == $this->get_en_passant() && $move->get_piece() == 'P')) {
        throw new RulesException("Cannot capture on empty square.");
      }

      if (!$move->get_capture() && $target_piece) {
        throw new RulesException("Target square is occupied.");
      }

      if ($target_piece && Board::get_color_of_piece($target_piece) == $this->get_active_color()) {
        throw new RulesException("Cannot capture player's own piece.");
      }
    }

    /**
    * Perform non-castling move.
    */
    private function standard_move(Move &$move) : void
    {

      $move_piece = $this->get_active_piece($move->get_piece());
      $target = $move->get_target(true);
      $target_piece = $this->get_square($target);

      $this->validate_capture_move($move);
      $origin_candidates = $this->get_move_origin_candidates($move);
      $origin = $this->get_move_origin($move, $origin_candidates);

      $new_board = $this->board->copy();

      $new_board->set_square($origin, '');
      if ($move->get_promotion()) {
        $new_board->set_square($target, $move->get_promotion());
      } else {
        $new_board->set_square($target, $move_piece);
        if ($target->export() == $this->get_en_passant()) {
          if ($this->get_active_color() == 'w') {
            $new_board->set_square($target->get_relative_square(0, -1), '');
          } else {
            $new_board->set_square($target->get_relative_square(0, 1), '');
          }
        }
      }

      $this->set_new_board($new_board);
      $move->set_origin($origin);
    }

    private function set_new_board(Board $new_board) : void
    {
      if ($new_board->is_check($this->get_active_color())) {
        throw new RulesException('King is in check.');
      }
      $this->set_board($new_board);
    }

    /**
    * Perform a move.
    */
    public function move(string $move) : void
    {
      $move = new Move($move);

      switch ($move->get_castling()) {
        case 'O-O':
          $this->castle_kingside($move);
          break;
        case 'O-O-O':
          $this->castle_queenside($move);
          break;
        default:
          $this->standard_move($move);
      }

      $this->after_move_update_halfmove($move);
      $this->after_move_set_en_passant($move);
      $this->after_move_update_castling_availability($move->get_piece(), $move->get_origin(true));
      $this->after_move_change_active_color();
    }

    private function after_move_update_castling_availability(string $piece_type, Square $origin_square) : void
    {
      $origin_file = $origin_square->get_file();
      if ($piece_type == 'R' && $origin_file == 'a') {
        $this->set_castling_availability($this->get_active_piece('Q'), false);
      } else if ($piece_type == 'R' && $origin_file == 'h') {
        $this->set_castling_availability($this->get_active_piece('K'), false);
      } else if ($piece_type == 'K' && $origin_file == 'e') {
        $this->set_castling_availability($this->get_active_piece('Q'), false);
        $this->set_castling_availability($this->get_active_piece('K'), false);
      }
    }

    private function after_move_change_active_color() : void
    {
      if ($this->get_active_color() == 'b') {
        $this->set_fullmove($this->get_fullmove() + 1);
        $this->set_active_color('w');
      } else {
        $this->set_active_color('b');
      }
    }

    private function after_move_set_en_passant(Move $move) : void
    {
      $move_piece = $this->get_active_piece($move->get_piece());
      $target = $move->get_target(true);
      $origin = $move->get_origin(true);

      if ($move_piece == 'P' && $origin->get_rank_index() == 1 && $target->get_rank_index() == 3) {
        $this->set_en_passant($target->get_relative_square(0, -1));
      } else if ($move_piece == 'p' && $origin->get_rank_index() == 6 && $target->get_rank_index() == 4) {
        $this->set_en_passant($target->get_relative_square(0, 1));
      } else {
        $this->set_en_passant('-');
      }
    }

    private function after_move_update_halfmove(Move $move) : void
    {
      if ($move->get_piece() == 'P' || $move->get_capture()) {
        $this->set_halfmove(0);
      } else {
        $this->set_halfmove($this->get_halfmove() + 1);
      }
    }

}

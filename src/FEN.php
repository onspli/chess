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
    private $en_passant;
    private $halfmove = 0;
    private $fullmove = 1;

    private $kings_rook_file = 'h';
    private $queens_rook_file = 'a';
    private $kings_file = 'e';
    private $use_shredder_fen = false;
    private $castling_rights = [ "K" => true, "Q" => true, "k" => true, "q" => true];

    /**
    * Load FEN (or Shredder-FEN) or setup starting position.
    * @param string $fen
    * @return void
    */
    function __construct(string $fen = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1')
    {
      $fen = trim($fen);
      $fen = preg_replace('/\s+/', ' ', $fen);
      $parts = explode(' ', $fen);
      if (sizeof($parts) < 4 || sizeof($parts) > 6) {
        throw new ParseException("FEN has " . sizeof($parts) . " fields. It must have at least 4 fields.");
      }

      $this->set_board($parts[0]);
      $this->set_active_color($parts[1]);
      $this->set_castling($parts[2]);
      $this->set_en_passant($parts[3]);
      $this->set_halfmove($parts[4] ?? 0);
      $this->set_fullmove($parts[5] ?? 1);
    }

    public function __clone()
  	{
  		$this->board = clone $this->board;
      $this->en_passant = clone $this->en_passant;
  	}

    /**
    * Export whole FEN string.
    * @return string FEN string
    */
    public function export() : string
    {
      return implode(' ', [$this->get_board(), $this->get_active_color(), $this->get_castling(), $this->get_en_passant(), $this->get_halfmove(), $this->get_fullmove()]);
    }

    /**
    * Export FEN string without halfmoves count and fullmove number.
    *
    * They really are not so important to describe chess position.
    */
    public function export_short() : string
    {
      return implode(' ', [$this->get_board(), $this->get_active_color(), $this->get_castling(), $this->get_en_passant()]);
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
    * Shredder-FEN is also supported - instead of K and Q the letters
    * of files containing rooks are used. For standard chess its AHah.
    * X-FEN is not supported.
    *
    * @return string castling availability string
    */
    public function get_castling() : string
    {
      if ($this->use_shredder_fen)
      {
        $str =
            ($this->castling_rights['Q'] ? strtoupper($this->queens_rook_file) : '') .
            ($this->castling_rights['K'] ? strtoupper($this->kings_rook_file) : '') .
            ($this->castling_rights['q'] ? $this->queens_rook_file : '').
            ($this->castling_rights['k'] ? $this->kings_rook_file : '');
        return $str ? $str : '-';
      }
      else
      {
        $str =
            ($this->castling_rights['K'] ? 'K' : '') .
            ($this->castling_rights['Q'] ? 'Q' : '') .
            ($this->castling_rights['k'] ? 'k' : '') .
            ($this->castling_rights['q'] ? 'q' : '');
        return $str ? $str : '-';
      }
    }

    /**
    * Set castling availability.
    *
    * If neither side can castle, this is "-".
    * Otherwise, this has one or more letters: "K" (White can castle kingside),
    * "Q" (White can castle queenside), "k" (Black can castle kingside), and/or
    * "q" (Black can castle queenside). A move that temporarily prevents castling
    * does not negate this notation.
    * Shredder-FEN is also supported - instead of K and Q the letters
    * of files containing rooks are used. For standard chess its AHah.
    * X-FEN is not supported.
    *
    * @param string castling availability string
    */
    public function set_castling(string $castling) : void
    {
      if (in_array($castling, ['-', 'KQkq', 'KQk', 'KQq', 'KQ', 'Kkq', 'Kk', 'Kq', 'K', 'Qkq', 'Qk', 'Qq', 'Q', 'kq', 'k', 'q'])) {
        $new_use_shredder_fen = false;
        $new_kings_rook_file = 'h';
        $new_queens_rook_file = 'a';
        $new_kings_file = 'e';
      } else {
        $new_use_shredder_fen = true;
        $new_kings_rook_file = null;
        $new_queens_rook_file = null;
        $new_kings_file = null;
      }
      $new_castling_rights = [ "K" => false, "Q" => false, "k" => false, "q" => false];

      if ($castling == '-') {
        $castling_rights = [];
      } else {
        $castling_rights = str_split($castling);
      }

      $white_king_square = $this->board->find_square_with_piece('K', true);
      $black_king_square = $this->board->find_square_with_piece('k', true);
      foreach ($castling_rights as $castling_right)
      {
        if ($new_use_shredder_fen && strtolower($castling_right) >= 'a' && strtolower($castling_right) <= 'h')
        {
          $new_kings_file = $this->board->find_square_with_piece(Board::get_piece_of_color('K', ctype_upper($castling_right) ? 'w' : 'b'), true)->get_file();
          $rooks_file = strtolower($castling_right);
          if ($rooks_file < $new_kings_file) {
            $castling_type = ctype_upper($castling_right)? 'Q' : 'q';
            $new_queens_rook_file = $rooks_file;
          } else {
            $castling_type = ctype_upper($castling_right)? 'K' : 'k';
            $new_kings_rook_file = $rooks_file;
          }
        }
        else if (!$new_use_shredder_fen)
        {
          $castling_type = $castling_right;
        }
        else
        {
          throw new ParseException("Invalid castling right '$castling_right' in string '$castling'.");
        }
        $new_castling_rights[$castling_type] = true;
      }

      $this->validate_castling($new_castling_rights, $new_kings_file, $new_kings_rook_file, $new_queens_rook_file);
      $this->kings_file = $new_kings_file;
      $this->kings_rook_file = $new_kings_rook_file;
      $this->queens_rook_file = $new_queens_rook_file;
      $this->castling_rights = $new_castling_rights;
      $this->use_shredder_fen = $new_use_shredder_fen;
    }

    private function validate_castling(array $castling_rights, $kings_file, $kings_rook_file, $queens_rook_file) : void
    {
      if ($kings_file === null || $kings_rook_file === null || $queens_rook_file === null)
      {
        throw new ParseException("Invalid castling string.");
      }

      if ($castling_rights['K'] || $castling_rights['Q'])
      {
        if ($this->board->get_square($kings_file.'1') != 'K') {
          throw new ParseException("Invalid castling string. White king not in initial position.");
        }
      }

      if ($castling_rights['k'] || $castling_rights['q'])
      {
        if ($this->board->get_square($kings_file.'8') != 'k') {
          throw new ParseException("Invalid castling string. Black king not in initial position.");
        }
      }

      if ($castling_rights['K'])
      {
        if ($this->board->get_square($kings_rook_file.'1') != 'R') {
          throw new ParseException("Invalid castling string. White king's rook not in initial position.");
        }
      }

      if ($castling_rights['k'])
      {
        if ($this->board->get_square($kings_rook_file.'8') != 'r') {
          throw new ParseException("Invalid castling string. Black king's rook not in initial position.");
        }
      }

      if ($castling_rights['Q'])
      {
        if ($this->board->get_square($queens_rook_file.'1') != 'R') {
          throw new ParseException("Invalid castling string. White queen's rook not in initial position.");
        }
      }

      if ($castling_rights['q'])
      {
        if ($this->board->get_square($queens_rook_file.'8') != 'r') {
          throw new ParseException("Invalid castling string. Black queen's rook not in initial position.");
        }
      }
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
    private function get_castling_availability(string $type) : bool
    {
      self::validate_castling_type($type);
      return $this->castling_rights[$type];
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
    private function set_castling_availability(string $type, bool $avalability) : void
    {
      self::validate_castling_type($type);
      $this->castling_rights[$type] = $avalability;
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
      // assert
      $this->validate_castling($this->castling_rights, $this->kings_file, $this->kings_rook_file, $this->queens_rook_file);

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

      return array_values(array_filter($pseudolegal_moves, [$this, 'is_legal_move']));
    }

    /**
    * Tests if move is legal.
    */
    public function is_legal_move(string $move) : bool
    {
      $fen = clone $this;
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
        $rank = 1;
      } else {
        $rank = 8;
      }

      $origin_king = new Square($this->kings_file.$rank);
      $target_king = new Square('g'.$rank);
      $origin_rook = new Square($this->kings_rook_file.$rank);
      $target_rook = new Square('f'.$rank);

      $this->castle_generic($move, $origin_king, $target_king, $origin_rook, $target_rook);
    }

    private function castle_queenside(Move &$move) : void
    {
      if (!$this->get_castling_availability($this->get_active_piece('Q'))) {
        throw new RulesException("Castling not available.");
      }

      if ($this->get_active_color() == 'w') {
        $rank = 1;
      } else {
        $rank = 8;
      }

      $origin_king = new Square($this->kings_file.$rank);
      $target_king = new Square('c'.$rank);
      $origin_rook = new Square($this->queens_rook_file.$rank);
      $target_rook = new Square('d'.$rank);

      $this->castle_generic($move, $origin_king, $target_king, $origin_rook, $target_rook);
    }

    private function castle_generic(Move &$move, Square $origin_king, Square $target_king, Square $origin_rook, Square $target_rook)
    {
      if (!$this->are_castling_squares_vacant($origin_king, $target_king, $origin_rook->get_file())) {
        throw new RulesException("Castling not available. There are pieces in the way of King.");
      }
      if (!$this->are_castling_squares_vacant($origin_rook, $target_rook, $origin_rook->get_file())) {
        throw new RulesException("Castling not available. There are pieces in the way of Rook.");
      }
      if (!$this->are_castling_squares_safe($origin_king, $target_king)) {
        throw new RulesException("Castling not available. King in check.");
      }

      $this->board->set_square($origin_king, '');
      $this->board->set_square($origin_rook, '');
      $this->board->set_square($target_rook, $this->get_active_piece('R'));
      $this->board->set_square($target_king, $this->get_active_piece('K'));

      $move->set_origin($origin_king);
    }

    private function are_castling_squares_vacant(Square $origin, Square $target, $allowed_rook_file)
    {
      $min_file = min($origin->get_file(), $target->get_file());
      $max_file = max($origin->get_file(), $target->get_file());

      $rank = $origin->get_rank();
      for ($file = $min_file; $file <= $max_file; $file++)
      {
        if ($file == $allowed_rook_file || $file == $this->kings_file) {
          continue;
        }
        $piece = $this->board->get_square($file.$rank);
        if ($piece) {
          return false;
        }
      }
      return true;
    }

    private function are_castling_squares_safe(Square $origin, Square $target)
    {
      $min_file = min($origin->get_file(), $target->get_file());
      $max_file = max($origin->get_file(), $target->get_file());

      $rank = $origin->get_rank();
      for ($file = $min_file; $file <= $max_file; $file++)
      {
        if ($this->board->is_square_attacked($file.$rank, Board::get_opposite_color($this->get_active_color()))) {
          return false;
        }
      }
      return true;
    }

    /**
    * Get array of origin squares candidates.
    * Square is origin square candidate if
    *  - it contains $piece
    *  - there is a pseudolegal move (ignoring king in check) of the $piece to $target_square
    * Returns array of Square objects.
    */
    private function get_move_origin_candidates(string $piece, Square $target_square, bool $is_capture) : array
    {
      if ($piece == 'P' && !$is_capture) {
        $origin_candidates = [$target_square->get_relative_square(0, -1)];
        if ($target_square->get_rank_index() == 3 && $this->board->is_square_vacant($target_square->get_relative_square(0, -1))) {
          $origin_candidates[] = $target_square->get_relative_square(0, -2);
        }
      } else if($piece == 'p' && !$is_capture) {
        $origin_candidates = [$target_square->get_relative_square(0, 1)];
        if ($target_square->get_rank_index() == 4 && $this->board->is_square_vacant($target_square->get_relative_square(0, 1))) {
          $origin_candidates[] = $target_square->get_relative_square(0, 2);
        }
      } else {
        $origin_candidates = $this->board->get_defended_squares($target_square, $this->get_opponents_piece($piece), true);
      }

      $is_piece_on_square = function($square) use ($piece) { return $this->get_square($square) == $piece; };
      return array_filter($origin_candidates, $is_piece_on_square);
    }

    /**
    * Get origin square of chess move.
    * Standard chess moves notation specifies only piece and target square and ommits origin square
    * when possible. We need to do some work to get the origin.
    */
    private function get_move_origin(Move $move) : Square
    {
      $piece = $this->get_active_piece($move->get_piece_type());
      $move_origin = $move->get_origin(true);

      $origin_candidates = $this->get_move_origin_candidates($piece, $move->get_target(true), $move->get_capture());

      $does_origin_candidate_comply_with_move_origin = function ($origin_candidate) use ($move_origin) {
        if ($move_origin->has_file() && $origin_candidate->get_file() != $move_origin->get_file()) {
          return false;
        }
        if ($move_origin->has_rank() && $origin_candidate->get_rank() != $move_origin->get_rank()) {
          return false;
        }
        return true;
      };

      $origin_candidates = array_filter($origin_candidates, $does_origin_candidate_comply_with_move_origin);

      $origin_candidates_legal = [];
      $active_color = $this->get_active_color();
      foreach ($origin_candidates as $origin)
      {
          $new_board = $this->get_new_board($move, $origin);
          if (!$new_board->is_check($active_color)) {
            $origin_candidates_legal[] = $origin;
          }
      }

      if (sizeof($origin_candidates_legal) == 0) {
        throw new RulesException("Invalid move.");
      }

      if (sizeof($origin_candidates_legal) > 1) {
        throw new RulesException("Ambiguous move.");
      }

      return reset($origin_candidates_legal);
    }

    private function validate_move_target_square(Move $move) : void
    {
      $target = $move->get_target(true);
      $target_piece = $this->get_square($target);

      if ($move->get_capture() && $target_piece == '' && !($target->export() == $this->get_en_passant() && $move->get_piece_type() == 'P')) {
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
      $this->validate_move_target_square($move);
      $origin = $this->get_move_origin($move);
      $new_board = $this->get_new_board($move, $origin);
      $this->set_new_board($new_board);
      $move->set_origin($origin);
    }

    private function get_new_board(Move $move, Square $origin) : Board
    {
      $new_board = clone $this->board;
      $piece = $this->get_active_piece($move->get_piece_type());
      $target = $move->get_target(true);

      $new_board->set_square($origin, '');
      if ($move->get_promotion()) {
        $new_board->set_square($target, Board::get_piece_of_color($move->get_promotion(), $this->get_active_color()));
      } else {
        $new_board->set_square($target, $piece);
        if ($move->get_piece_type() == 'P' && $target->export() == $this->get_en_passant()) {
          if ($this->get_active_color() == 'w') {
            $new_board->set_square($target->get_relative_square(0, -1), '');
          } else {
            $new_board->set_square($target->get_relative_square(0, 1), '');
          }
        }
      }
      return $new_board;
    }

    private function set_new_board(Board $new_board) : void
    {
      $active_color = $this->get_active_color();
      if ($new_board->is_check($active_color)) {
        throw new RulesException('King is in check. ' . $new_board->export() . ' ' . $active_color);
      }
      $this->set_board($new_board);
    }

    /**
    * Perform a move.
    */
    public function move(string $move) : void
    {
      // assert
      $this->validate_castling($this->castling_rights, $this->kings_file, $this->kings_rook_file, $this->queens_rook_file);

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
      $this->after_move_update_castling_availability($move->get_piece_type(), $move->get_origin(true), $move->get_target(true));
      $this->after_move_change_active_color();
    }

    private function after_move_update_castling_availability(string $piece_type, Square $origin_square, Square $target_square) : void
    {
      $active_color = $this->get_active_color();
      if ($active_color == 'w') {
        $active_first_rank = '1';
        $opponents_first_rank = '8';
      } else {
        $active_first_rank = '8';
        $opponents_first_rank = '1';
      }

      // moving king or rooks prevents castling
      $origin_square_str = $origin_square->export();
      if ($piece_type == 'R' && $origin_square_str == $this->queens_rook_file.$active_first_rank) {
        $this->set_castling_availability($this->get_active_piece('Q'), false);
      } else if ($piece_type == 'R' && $origin_square_str == $this->kings_rook_file.$active_first_rank) {
        $this->set_castling_availability($this->get_active_piece('K'), false);
      } else if ($piece_type == 'K' && $origin_square_str == $this->kings_file.$active_first_rank) {
        $this->set_castling_availability($this->get_active_piece('Q'), false);
        $this->set_castling_availability($this->get_active_piece('K'), false);
      }

      // capturing opponents rooks prevents castling
      $target_square_str = $target_square->export();
      if ($target_square_str == $this->queens_rook_file.$opponents_first_rank) {
        $this->set_castling_availability($this->get_opponents_piece('Q'), false);
      } else if ($target_square_str == $this->kings_rook_file.$opponents_first_rank) {
        $this->set_castling_availability($this->get_opponents_piece('K'), false);
      }

      // assert
      $this->validate_castling($this->castling_rights, $this->kings_file, $this->kings_rook_file, $this->queens_rook_file);
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
      $move_piece = $this->get_active_piece($move->get_piece_type());
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
      if ($move->get_piece_type() == 'P' || $move->get_capture()) {
        $this->set_halfmove(0);
      } else {
        $this->set_halfmove($this->get_halfmove() + 1);
      }
    }

}

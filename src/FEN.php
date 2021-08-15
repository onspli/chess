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
    function __construct(string $fen = '')
    {
      if ($fen)
      {
        $fen = trim($fen);
        $fen = preg_replace('/\s+/', ' ', $fen);
        $parts = explode(' ', $fen);
        if (sizeof($parts) != 6) {
          throw new ParseException("FEN has " . sizeof($parts) . " fields. It must have 6 fields.");
        }

        $this->set_board($parts[0]);
        $this->set_active($parts[1]);
        $this->set_castling($parts[2]);
        $this->set_en_passant($parts[3]);
        $this->set_halfmove($parts[4]);
        $this->set_fullmove($parts[5]);
      }
      else
      {
        $this->set_board('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR');
        $this->set_en_passant('-');
      }
    }

    /**
    * Export whole FEN string.
    * @return string FEN string
    */
    public function export() : string
    {
      return implode(' ', [$this->board(), $this->active(), $this->castling(), $this->en_passant(), $this->halfmove(), $this->fullmove()]);
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
    public function board(bool $as_object = false)
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
    public function square($square) : string
    {
      return $this->board->square($square);
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
    public function active() : string
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
    public function set_active(string $color) : void
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
    public function castling() : string
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
    public function set_castling(string $castling) : void
    {
      if (!in_array($castling, ['-', 'KQkq', 'KQk', 'KQq', 'KQ', 'Kkq', 'Kk', 'Kq', 'K', 'Qkq', 'Qk', 'Qq', 'Q', 'kq', 'k', 'q'])) {
        throw new ParseException("Invalid castling string '$castling'.");
      }
      $this->castling = $castling;
    }

    public function castling_availability(string $type) : bool
    {
      if (!in_array($type, ['K', 'Q', 'k', 'q'])) {
        throw new ParseException("Invalid castling type '$type'.");
      }
      $castling = str_split($this->castling);
      return in_array($type, $castling);
    }

    public function set_castling_availability(string $type, bool $avalability) : void
    {
      if (!in_array($type, ['K', 'Q', 'k', 'q'])) {
        throw new ParseException("Invalid castling type '$type'.");
      }
      if ($this->castling_availability($type) === $avalability) {
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

      $this->set_castling($castling);
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
    public function en_passant(bool $as_object = false)
    {
      if ($as_object) {
        return $this->en_passant;
      }
      return $this->en_passant->alg();
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
      if ($square->is_null() == false && $square->rank() != 2 && $square->rank() != 5) {
        throw new ParseException("Invalid En passant square '".$square->alg()."'.");
      }
      $this->en_passant = $square;
    }

    /**
    * Get Halfmove clock
    *
    * The number of halfmoves since the last capture or pawn
    * advance, used for the fifty-move rule.
    */
    public function halfmove() : int
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
    public function fullmove() : int
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
      return $this->is_check() && sizeof($this->possible_moves()) == 0;
    }

    /**
    * Returns true if king of active color is in stalemate.
    */
    public function is_stalemate() : bool
    {
      return !$this->is_check() && sizeof($this->possible_moves()) == 0;
    }

    /**
    * Returns true if fifty move rule draw can be claimed by active color.
    */
    public function is_fifty_move() : bool
    {
      return $this->halfmove() >= 100;
    }

    /**
    * Returns true if king of active color is in check.
    */
    public function is_check() : bool
    {
      return $this->board->is_check($this->active());
    }

    private function active_piece(string $piece) : string
    {
      return Board::active_piece($piece, $this->active());
    }

    private function opponents_piece(string $piece) : string
    {
      return Board::opponents_piece($piece, $this->active());
    }

    private function is_active_piece(string $piece) : bool
    {
      return $this->active_piece($piece) == $piece;
    }

    /**
    * Array of all possible moves in current position.
    */
    public function possible_moves() : array
    {
      $candidate_moves = [];

      $candidate_moves_for_piece = function($moving_piece) use (&$candidate_moves) {
        $reachable_squares_origins = [];
        for ($rank = 0; $rank < 8; $rank ++) {
          for ($file = 0; $file < 8; $file ++) {
            $origin_square = new Square($file, $rank);
            $piece = $this->square($origin_square);
            if ($piece != $moving_piece) {
              continue;
            }

            $reachable_squares = $this->board->reachable_squares($origin_square, $moving_piece);
            foreach ($reachable_squares as $reachable_square) {
              if (!array_key_exists($reachable_square, $reachable_squares_origins)) {
                $reachable_squares_origins[$reachable_square] = [];
              }
              $reachable_squares_origins[$reachable_square][] = $origin_square;
            }
          }
        }
        foreach ($reachable_squares_origins as $reachable_square => $origins) {
          $capture = '';
          if ($this->square($reachable_square)) {
            $capture = 'x';
          }
          if (sizeof($origins) == 1) {
            $candidate_moves[] = strtoupper($moving_piece) . $capture . $reachable_square;
          } else if (sizeof($origins) == 2) {
            if ($origins[0]->file() != $origins[1]->file()) {
              $candidate_moves[] = strtoupper($moving_piece) . $origins[0]->file() . $capture . $reachable_square;
              $candidate_moves[] = strtoupper($moving_piece) . $origins[1]->file() . $capture . $reachable_square;
            } else {
              $candidate_moves[] = strtoupper($moving_piece) . $origins[0]->rank() . $capture . $reachable_square;
              $candidate_moves[] = strtoupper($moving_piece) . $origins[1]->rank() . $capture . $reachable_square;
            }
          } else {
            foreach ($origins as $origin) {
              $candidate_moves[] = strtoupper($moving_piece) . $origin->alg() . $capture . $reachable_square;
            }
          }
        }
      };

      $candidate_moves_for_pawn = function($moving_piece) use (&$candidate_moves) {
        for ($rank = 0; $rank < 8; $rank ++) {
          for ($file = 0; $file < 8; $file ++) {
            $origin_square = new Square($file, $rank);
            $piece = $this->square($origin_square);
            if ($piece != $moving_piece) {
              continue;
            }

            $reachable_squares = $this->board->reachable_squares($origin_square, $moving_piece, $this->en_passant());
            foreach ($reachable_squares as $reachable_square) {

              if (($this->active() == 'w' && $origin_square->rank() == 6) ||
                  ($this->active() == 'b' && $origin_square->rank() == 1)) {
                    $promotions = ['=N', '=B', '=R', '=Q'];
              } else {
                $promotions = [''];
              }
              foreach ($promotions as $promotion) {
                if ($this->square($reachable_square)) {
                  $candidate_moves[] = $origin_square->file() . 'x' . $reachable_square . $promotion;
                } else {
                  $candidate_moves[] = $reachable_square . $promotion;
                }
              }
            }
          }
        }
      };

      $candidate_moves_for_pawn($this->active_piece('P'));
      $candidate_moves_for_piece($this->active_piece('N'));
      $candidate_moves_for_piece($this->active_piece('B'));
      $candidate_moves_for_piece($this->active_piece('R'));
      $candidate_moves_for_piece($this->active_piece('Q'));
      $candidate_moves_for_piece($this->active_piece('K'));
      if ($this->castling_availability($this->active_piece('Q'))) {
        $candidate_moves[] = 'O-O-O';
      }
      if ($this->castling_availability($this->active_piece('K'))) {
        $candidate_moves[] = 'O-O';
      }
      $possible_moves = [];
      foreach ($candidate_moves as $candidate_move) {
        $fen = new static($this->export());
        try {
          $fen->move($candidate_move);
          $possible_moves[] = $candidate_move;
        } catch (\Exception $e) {};
      }
      sort($possible_moves);
      return $possible_moves;
    }

    /**
    * Perform a move.
    */
    public function move(string $move) : void
    {
      $move = new Move($move);
      $move_piece = $this->active_piece($move->piece());

      if ($move->castling() == 'O-O')
      {

        if (!$this->castling_availability($this->active_piece('K'))) {
          throw new RulesException("Castling not available.");
        }

        if ($this->active() == 'w') {
          $origin = new Square('e1');
        } else {
          $origin = new Square('e8');
        }

        if ($this->square($origin) != $this->active_piece('K')) {
          throw new RulesException("Castling not available. King not in initial position.");
        }
        if ($this->square($origin->rel(1, 0)) || $this->square($origin->rel(2, 0))) {
          throw new RulesException("Castling not available. There are pieces in the way.");
        }
        if ($this->square($origin->rel(3, 0)) != $this->active_piece('R')) {
          throw new RulesException("Castling not available. Rook not in initial position");
        }
        if ($this->is_check()) {
          throw new RulesException("Castling not available. King in check.");
        }

        $new_board = $this->board->copy();
        $new_board->set_square($origin, '');
        $new_board->set_square($origin->rel(1, 0), $this->active_piece('K'));
        if ($new_board->is_check($this->active())) {
          throw new RulesException("Castling not available. King in check.");
        }

        $new_board->set_square($origin->rel(1, 0), $this->active_piece('R'));
        $new_board->set_square($origin->rel(3, 0), '');
        $new_board->set_square($origin->rel(2, 0), $this->active_piece('K'));

      }
      else if ($move->castling() == 'O-O-O')
      {

        if (!$this->castling_availability($this->active_piece('Q'))) {
          throw new RulesException("Castling not available.");
        }

        if ($this->active() == 'w') {
          $origin = new Square('e1');
        } else {
          $origin = new Square('e8');
        }

        if ($this->square($origin) != $this->active_piece('K')) {
          throw new RulesException("Castling not available. King not in initial position.");
        }
        if ($this->square($origin->rel(-1, 0)) || $this->square($origin->rel(-2, 0)) || $this->square($origin->rel(-3, 0))) {
          throw new RulesException("Castling not available. There are pieces in the way.");
        }
        if ($this->square($origin->rel(-4, 0)) != $this->active_piece('R')) {
          throw new RulesException("Castling not available. Rook not in initial position");
        }
        if ($this->is_check()) {
          throw new RulesException("Castling not available. King in check.");
        }

        $new_board = $this->board->copy();
        $new_board->set_square($origin, '');
        $new_board->set_square($origin->rel(-1, 0), $this->active_piece('K'));
        if ($new_board->is_check($this->active())) {
          throw new RulesException("Castling not available. King in check.");
        }

        $new_board->set_square($origin->rel(-1, 0), $this->active_piece('R'));
        $new_board->set_square($origin->rel(-4, 0), '');
        $new_board->set_square($origin->rel(-2, 0), $this->active_piece('K'));

      } else {

        $target = $move->target(true);
        $target_piece = $this->square($target);

        if ($move->capture() && $target_piece == '' && !($target->alg() == $this->en_passant() && $move->piece() == 'P')) {
          throw new RulesException("Cannot capture on empty square.");
        }

        if (!$move->capture() && $target_piece) {
          throw new RulesException("Target square is occupied.");
        }

        if ($target_piece && $this->is_active_piece($target_piece)) {
          throw new RulesException("Cannot capture player's own piece.");
        }

        if ($move_piece == 'P' && !$move->capture()) {
          $origin_candidates = [$target->rel(0, -1)];
          if ($target->rank() == 3 && $this->square($target->rel(0, -1)) == '') {
            $origin_candidates[] = $target->rel(0, -2);
          }
        } else if($move_piece == 'p' && !$move->capture()) {
          $origin_candidates = [$target->rel(0, 1)];
          if ($target->rank() == 4 && $this->square($target->rel(0, 1)) == '') {
            $origin_candidates[] = $target->rel(0, 2);
          }
        } else {
          $origin_candidates = $this->board->attacked_squares($target, $this->opponents_piece($move_piece), true);
        }

        $origin_candidates2 = [];
        foreach ($origin_candidates as $origin_candidate) {
          if ($this->square($origin_candidate) == $move_piece) {
            if ($move->origin_file(true) !== null && $origin_candidate->file() != $move->origin_file(true)) {
              continue;
            }
            if ($move->origin_rank(true) !== null && $origin_candidate->rank() != $move->origin_rank(true)) {
              continue;
            }
            $origin_candidates2[] = $origin_candidate;
          }
        }

        if (sizeof($origin_candidates2) == 0) {
          throw new RulesException("Invalid move.");
        }

        if (sizeof($origin_candidates2) > 1) {
          throw new RulesException("Ambiguous move.");
        }

        $origin = $origin_candidates2[0];
        $new_board = $this->board->copy();

        $new_board->set_square($origin, '');
        if ($move->promotion()) {
          $new_board->set_square($target, $move->promotion());
        } else {
          $new_board->set_square($target, $move_piece);
          if ($target->alg() == $this->en_passant()) {
            if ($this->active() == 'w') {
              $new_board->set_square($target->rel(0, -1), '');
            } else {
              $new_board->set_square($target->rel(0, 1), '');
            }
          }
        }

      }

      if ($new_board->is_check($this->active())) {
        throw new RulesException('King is in check.');
      }

      $this->set_board($new_board);

      if ($move->piece() == 'P' || $move->capture()) {
        $this->set_halfmove(0);
      } else {
        $this->set_halfmove($this->halfmove() + 1);
      }

      if ($move_piece == 'P' && $origin->rank() == 1 && $target->rank() == 3) {
        $this->set_en_passant($target->rel(0, -1));
      } else if ($move_piece == 'p' && $origin->rank() == 6 && $target->rank() == 4) {
        $this->set_en_passant($target->rel(0, 1));
      } else {
        $this->set_en_passant('-');
      }

      if ($move->piece() == 'R') {
        switch ($origin->alg()) {
          case 'a1':
            $this->set_castling_availability('Q', false);
            break;
          case 'h1':
            $this->set_castling_availability('K', false);
            break;
          case 'a8':
            $this->set_castling_availability('q', false);
            break;
          case 'h8':
            $this->set_castling_availability('k', false);
            break;
        }
      } else if ($move->piece() == 'K') {
        switch ($origin->alg()) {
          case 'e1':
            $this->set_castling_availability('Q', false);
            $this->set_castling_availability('K', false);
            break;
          case 'e8':
            $this->set_castling_availability('q', false);
            $this->set_castling_availability('k', false);
            break;
        }
      }

      if ($this->active() == 'b') {
        $this->set_fullmove($this->fullmove() + 1);
        $this->set_active('w');
      } else {
        $this->set_active('b');
      }


    }

}

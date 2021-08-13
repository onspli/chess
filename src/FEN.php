<?php

namespace Onspli\Chess;

class FEN
{
    private $board;
    private $active = 'w';
    private $castling = 'KQkq';
    private $en_passant;
    private $halfmove = 0;
    private $fullmove = 1;

    /**
    * Load fen or setup starting position.
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
    * Export whole FEN string
    */
    public function export() : string
    {
      return implode(' ', [$this->board(), $this->active(), $this->castling(), $this->en_passant(), $this->halfmove(), $this->fullmove()]);
    }

    /**
    * Preview of the board in ASCII graphics.
    * @codeCoverageIgnore
    */
    public function preview() : string
    {
      $preview = '';
      for ($rank = 8; $rank >= 1; $rank --) {
        for ($file = 1; $file <= 8; $file ++) {
          $piece = $this->square(new Square($file, $rank));
          if (!$piece) {
            $piece = '.';
          }
          $preview .= $piece;
        }
        $preview .= "\n";
      }
      return $preview;
    }

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
    public function board(bool $as_object = false) : string
    {
      if ($as_object) {
        return $this->board;
      }
      return $this->board->export();
    }

    public function set_board($pieces) : void
    {
      $board = new Board($pieces);
      $this->board = $board;
    }

    public function square($square) : string
    {
      return $this->board->square($square);
    }

    public function set_square($square, string $piece) : void
    {
      $this->board->set_square($square, $piece);
    }

    /**
    * Active color. "w" means White moves next, "b" means Black moves next.
    */
    public function active() : string
    {
      return $this->active;
    }

    public function set_active(string $color) : void
    {
      if ($color != 'w' && $color != 'b') {
        throw new ParseException("Active color must be either 'w' or 'b', it is '$color'.");
      }
      $this->active = $color;
    }

    /**
    * Castling availability. If neither side can castle, this is "-".
    * Otherwise, this has one or more letters: "K" (White can castle kingside),
    * "Q" (White can castle queenside), "k" (Black can castle kingside), and/or
    * "q" (Black can castle queenside). A move that temporarily prevents castling
    * does not negate this notation.
    */
    public function castling() : string
    {
      return $this->castling;
    }

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
    * En passant target square in algebraic notation. If there's no en passant
    * target square, this is "-". If a pawn has just made a two-square move,
    * this is the position "behind" the pawn. This is recorded regardless of
    * whether there is a pawn in position to make an en passant capture.
    */
    public function en_passant() : string
    {
      return $this->en_passant->alg();
    }

    public function set_en_passant(string $square) : void
    {
      $square = new Square($square);
      if ($square->is_null() == false && $square->rank() != 2 && $square->rank() != 5) {
        throw new ParseException("Invalid En passant square '".$square->alg()."'.");
      }
      $this->en_passant = $square;
    }

    /**
    * Halfmove clock: The number of halfmoves since the last capture or pawn
    * advance, used for the fifty-move rule.
    */
    public function halfmove() : int
    {
      return $this->halfmove;
    }

    public function set_halfmove($halfmove) : void
    {
      if (intval($halfmove) != $halfmove || $halfmove < 0) {
        throw new ParseException("Halfmove clock '$halfmove' must be non-negative integer.");
      }
      $this->halfmove = $halfmove;
    }

    /**
    * Fullmove number: The number of the full move. It starts at 1, and is
    * incremented after Black's move.
    */
    public function fullmove() : int
    {
      return $this->fullmove;
    }

    public function set_fullmove($fullmove) : void
    {
      if (intval($fullmove) != $fullmove || $fullmove <= 0) {
        throw new ParseException("Fullmove number '$fullmove' must be positive integer.");
      }
      $this->fullmove = $fullmove;
    }

    /**
    * Returns true if king of active color is in mate.
    * @codeCoverageIgnore
    */
    public function is_mate() : bool
    {
      return $this->is_check() && sizeof($this->possible_moves()) == 0;
    }

    /**
    * Returns true if king of active color is in stalemate.
    * @codeCoverageIgnore
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
    * @codeCoverageIgnore
    */
    public function is_check() : bool
    {
      throw new NotImplementedException;
    }

    /**
    * Array of all possible moves in current position.
    * @codeCoverageIgnore
    */
    public function possible_moves() : array
    {
      throw new NotImplementedException;
    }

    /**
    * Perform a move.
    * @codeCoverageIgnore
    */
    public function move(string $move) : void
    {
      throw new NotImplementedException;
    }

}

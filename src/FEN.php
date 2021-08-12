<?php

namespace Onspli\Chess;

class FEN
{

    /**
    * 8x8 array representing chess board
    * [rank1, rank2, ..., rank8]
    */
    private $board = [];

    private $active = 'w';
    private $castling = 'KQkq';
    private $en_passant = '-';
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
        if (sizeof($parts) != 6) throw new ExceptionParse("FEN has " . sizeof($parts) . " fields. It must have 6 fields.");

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
          $piece = $this->piece(self::square($file, $rank));
          if (!$piece) $piece = '.';
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
    public function board() : string
    {
      $pieces = '';
      for ($rank = 8; $rank >= 1; $rank --) {
        $space = 0;
        for ($file = 1; $file <= 8; $file ++) {
          $piece = $this->piece(self::square($file, $rank));
          if (!$piece) {
            $space++;
          } else {
            if ($space > 0) $pieces .= $space;
            $pieces .= $piece;
            $space = 0;
          }
        }
        if ($space > 0) $pieces .= $space;
        if ($rank > 1) $pieces .= '/';
      }
      return $pieces;
    }

    public function set_board(string $pieces) : void
    {
      $pieces = trim($pieces);
      $pieces = preg_replace('/\s+/', '', $pieces);
      $ranks = explode('/', $pieces);
      if (sizeof($ranks) != 8) throw new ExceptionParse("Wrong number of ranks " . sizeof($ranks) . ".");

      $empty_rank = array_fill(0, 8, '');
      $board = array_fill(0, 8, $empty_rank);

      $rank = 8;
      foreach ($ranks as $rank_pieces)
      {
        $file = 1;
        foreach (str_split($rank_pieces) as $piece)
        {
          if (is_numeric($piece))
          {
            $file += intval($piece);
          }
          else
          {
            if (!in_array($piece, ['P', 'N', 'B', 'R', 'Q', 'K', 'p', 'n', 'b', 'r', 'q', 'k'])) throw new \Expcetion("Invalid piece '$piece'.");
            $board[$file - 1][$rank - 1] = $piece;
            $file += 1;
          }
          if ($file > 9) throw new ExceptionParse("Too many pieces on rank.");
        }
        $rank -= 1;
      }
      $this->board = $board;
    }

    public function piece(string $square) : string
    {
      return $this->board[self::file($square) - 1][self::rank($square) - 1];
    }

    public function set_piece(string $square, string $piece) : void
    {
      if (!in_array($piece, ['', 'P', 'N', 'B', 'R', 'Q', 'K', 'p', 'n', 'b', 'r', 'q', 'k'])) throw new \Expcetion("Invalid piece '$piece'.");
      $this->board[self::file($square) - 1][self::rank($square) - 1] = $piece;
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
      if ($color != 'w' && $color != 'b') throw new ExceptionParse("Active color must be either 'w' or 'b', it is '$color'.");
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
      if (!in_array($castling, ['-', 'KQkq', 'KQk', 'KQq', 'KQ', 'Kkq', 'Kk', 'Kq', 'K', 'Qkq', 'Qk', 'Qq', 'Q', 'kq', 'k', 'q']))
        throw new ExceptionParse("Invalid castling string '$castling'.");
      $this->castling = $castling;
    }

    public function castling_availability(string $type) : bool
    {
      if (!in_array($type, ['K', 'Q', 'k', 'q'])) throw new ExceptionParse("Invalid castling type '$type'.");
      $castling = str_split($this->castling);
      return in_array($type, $castling);
    }

    public function set_castling_availability(string $type, bool $avalability) : void
    {
      if (!in_array($type, ['K', 'Q', 'k', 'q'])) throw new ExceptionParse("Invalid castling type '$type'.");
      if ($this->castling_availability($type) === $avalability) return;

      // convert str to array of available types
      if ($this->castling == '-') $castling = [];
      else $castling = str_split($this->castling);

      // add or remove castling availability for type
      if ($avalability === false) $castling = array_diff($castling, [$type]);
      else $castling = array_merge($castling, [$type]);

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
      return $this->en_passant;
    }

    public function set_en_passant(string $square) : void
    {
      if ($square != '-')
      {
        self::validate_square($square);
        $rank = self::rank($square);
        if ($rank != 3 && $rank != 6) throw new ExceptionParse("Invalid En passant square '$square'.");
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
      if (intval($halfmove) != $halfmove || $halfmove < 0) throw new ExceptionParse("Halfmove clock '$halfmove' must be non-negative integer.");
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
      if (intval($fullmove) != $fullmove || $fullmove <= 0) throw new ExceptionParse("Fullmove number '$fullmove' must be positive integer.");
      $this->fullmove = $fullmove;
    }

    /**
    * Returns true if king of active color is in check.
    * @codeCoverageIgnore
    */
    public function is_check() : bool
    {
      throw new ExceptionNotImplemented;
    }

    /**
    * Returns true if king of active color is in mate.
    * @codeCoverageIgnore
    */
    public function is_mate() : bool
    {
      throw new ExceptionNotImplemented;
    }

    /**
    * Returns true if king of active color is in stalemate.
    * @codeCoverageIgnore
    */
    public function is_stalemate() : bool
    {
      throw new ExceptionNotImplemented;
    }

    /**
    * Returns true if fifty move rule draw can be claimed by active color.
    * @codeCoverageIgnore
    */
    public function is_fifty_move() : bool
    {
      throw new ExceptionNotImplemented;
    }

    /**
    * Array of all possible moves in current position.
    * @codeCoverageIgnore
    */
    public function possible_moves() : array
    {
      throw new ExceptionNotImplemented;
    }

    /**
    * Perform a move.
    * @codeCoverageIgnore
    */
    public function move(string $move) : void
    {
      throw new ExceptionNotImplemented;
    }

    protected static function validate_square(string $square) : void
    {
      if (strlen($square) != 2) throw new ExceptionParse("Invalid square '$square'.");
      $file = $square[0];
      if (ord($file) < ord('a') || ord($file) > ord('h')) throw new ExceptionParse("Invalid square '$square'. File '$file' is not valid.");
      $rank = intval($square[1]);
      if ($rank < 1 || $rank > 8) throw new ExceptionParse("Invalid square '$square'. Rank '$rank' is not valid.");
    }

    protected static function rank(string $square) : int
    {
      self::validate_square($square);
      return intval($square[1]);
    }

    protected static function file(string $square) : int
    {
      self::validate_square($square);
      return intval(ord($square[0]) - ord('a') + 1);
    }

    protected static function square($file, $rank) : string
    {
      if (intval($file) != $file || $file < 1 || $file > 8) throw new ExceptionParse("File number '$file' must be integer between 1 and 8.");
      if (intval($rank) != $rank || $rank < 1 || $rank > 8) throw new ExceptionParse("Rank number '$rank' must be integer between 1 and 8.");
      return chr(ord('a') + $file - 1) . $rank;
    }

}

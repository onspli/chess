<?php

namespace Onspli\Chess;


/**
* Portable Game Notation (PGN) is a standard plain text format for recording chess games.
*
* Portable Game Notation (PGN) is a standard plain text format for recording
* chess games (both the moves and related data), which can be read by humans
* and is also supported by most chess software.
*/
class PGN
{
  protected $tags = [];
  protected $halfmoves = [];
  protected $fens = [];
  protected $initial_fen;
  protected $initial_halfmove_number = 1;

  /**
  * Initialize object from PGN string.
  */
  function __construct(string $pgn = '')
  {
    $this->unset_initial_fen();
    $this->parse_tag_section($pgn);
    $movetext_section = $this->get_movetext_section($pgn);
    if ($movetext_section) {
      $halfmoves = explode(' ', $movetext_section);
      array_walk($halfmoves, [self::class, 'move']);
    }
  }

  /**
  * Export PGN string.
  */
  public function export() : string
  {
    return $this->export_tags() . $this->export_movetext();
  }

  /**
  * Export movetext section of pgn.
  */
  public function export_movetext() : string
  {
    $pgn = '';
    for ($halfmove_number = $this->get_initial_halfmove_number(); $halfmove_number <= $this->get_current_halfmove_number(); $halfmove_number++) {
      $move_number = ceil($halfmove_number / 2);
      if ($halfmove_number % 2 == 0 && $halfmove_number == $this->get_initial_halfmove_number()) {
        $pgn .= $move_number . '... ';
      } else if ($halfmove_number % 2 == 1) {
        $pgn .= $move_number . '. ';
      }
      $pgn .= $this->get_halfmove($halfmove_number) . ' ';
    }
    return trim($pgn);
  }

  /**
  * Export tag pairs section (headers) of PGN.
  */
  public function export_tags() : string
  {
    $pgn = '';
    foreach ($this->tags as $name => $value) {
      $pgn .= '[' . $name . ' "' . $value . '"]' . PHP_EOL;
    }
    return $pgn;
  }

  /**
  * Validate all moves make sense according to chess rules.
  */
  public function validate_moves() : void
  {
    $this->get_current_fen();
  }

  /**
  * Set tag pair (header).
  */
  public function set_tag(string $name, ?string $value) : void
  {
    if ($value !== null) {
      $this->tags[$name] = $value;
      if ($name == 'FEN') {
        $this->set_initial_fen($value);
      }
    } else if (isset($this->tags[$name])) {
      unset($this->tags[$name]);
      if ($name == 'FEN') {
        $this->unset_initial_fen();
      }
    }
  }

  /**
  * Set custom initial position.
  */
  public function set_initial_fen(string $fen) : void
  {
    $this->initial_fen = new FEN($fen);
    $this->tags['FEN'] = $this->initial_fen->export();
    $this->initial_halfmove_number = self::get_halfmove_number($this->initial_fen->get_fullmove(), $this->initial_fen->get_active_color());
    // chached fens depend on initial position, clear cache
    $this->fens = [];
  }

  /**
  * Unset custom initial position - use the standard initial position.
  */
  public function unset_initial_fen() : void
  {
    $this->initial_fen = new FEN;
    if (isset($this->tags['FEN'])) {
      unset($this->tags['FEN']);
    }
    $this->initial_halfmove_number = 1;
    // chached fens depend on initial position, clear cache
    $this->fens = [];
  }

  /**
  * Remove tag pair (header).
  */
  public function unset_tag(string $name) : void
  {
    $this->set_tag($name, null);
  }

  /**
  * Read tag pair (header) value.
  */
  public function get_tag(string $name) : ?string
  {
    if (!isset($this->tags[$name])) {
      return null;
    }
    return $this->tags[$name];
  }

  /**
  * Get initial position.
  */
  public function get_initial_fen(bool $as_object = false)
  {
    if ($as_object) {
      return $this->initial_fen->copy();
    }
    return $this->initial_fen->export();
  }

  private function get_halfmove_index(int $halfmove_number) : int
  {
    return $halfmove_number - $this->get_initial_halfmove_number();
  }

  /**
  * Get FEN of current position.
  *
  * Return position after last move.
  */
  public function get_current_fen(bool $as_object = false)
  {
    return $this->get_fen_after_halfmove($this->get_current_halfmove_number(), $as_object);
  }

  /**
  * Get poosition after given halfmove.
  */
  public function get_fen_after_halfmove(int $halfmove_number, bool $as_object = false)
  {
    if ($halfmove_number == 0) {
      return $this->get_initial_fen($as_object);
    }
    $halfmove_index = $this->get_halfmove_index($halfmove_number);
    $this->compute_fens($halfmove_number);
    if (!isset($this->fens[$halfmove_index])) {
      throw new \OutOfBoundsException;
    }
    $fen = $this->fens[$halfmove_index];
    if ($as_object) {
      return $fen->copy();
    }
    return $fen->export();
  }

  public function get_current_halfmove_number() : int
  {
    return sizeof($this->halfmoves) + $this->get_initial_halfmove_number() - 1;
  }

  public function get_initial_halfmove_number() : int
  {
    return $this->initial_halfmove_number;
  }

  private function get_last_computed_fen() : FEN
  {
    $computed = sizeof($this->fens);
    if ($computed == 0) {
      return $this->get_initial_fen(true);
    }
    return $this->fens[$computed - 1]->copy();
  }

  private function compute_fens(int $max_halfmove_to_compute) : void
  {
    if ($max_halfmove_to_compute > $this->get_current_halfmove_number()) {
      throw new \OutOfBoundsException;
    }
    $fen = $this->get_last_computed_fen();
    for ($halfmove_number = sizeof($this->fens) + $this->get_initial_halfmove_number(); $halfmove_number <= $max_halfmove_to_compute; $halfmove_number ++) {
      $halfmove = $this->get_halfmove($halfmove_number);
      try {
        $fen->move($halfmove);
      } catch (\Exception $e) {
        throw new \Exception('Move ' . ceil($halfmove_number / 2) . ($halfmove_number % 2 ? '. ' : '... ') . $halfmove . ' is invalid. FEN ' . $this->get_fen_after_halfmove($halfmove_number - 1), 0, $e);
      }
      $this->fens[] = $fen->copy();
    }
  }

  /**
  * Get move in standard algebraic notation.
  *
  * Halfmove number starts with 1 (white's first move). One move has
  * two halfmoves (for white and black player).
  */
  public function get_halfmove(int $halfmove_number, bool $as_object = false)
  {
    $halfmove_index = $this->get_halfmove_index($halfmove_number);
    if (!isset($this->halfmoves[$halfmove_index])) {
      throw new \OutOfBoundsException;
    }
    $halfmove = $this->halfmoves[$halfmove_index];
    if ($as_object) {
      return $halfmove;
    }
    return $halfmove->export();
  }


  /**
  * Converts (move number, color) to halfmove number.
  */
  static public function get_halfmove_number(int $move_number, string $color) : int
  {
    if ($color == 'w') {
      $halfmove_number = $move_number * 2 - 1;
    } else {
      $halfmove_number = $move_number * 2;
    }
    return $halfmove_number;
  }


  /**
  * Perform move.
  *
  * The method validates syntax of the move, however it doesn't check
  * the move is valid acording to chess rules.
  */
  public function move(string $move) : void
  {
    $this->halfmoves[] = new Move($move);
  }

  /**
  * Parse tag section and save all tag values.
  * The method does NOT clear existing tags, however it overrides them.
  */
  private function parse_tag_section(string $pgn) : void
  {
    $tags = [];
    preg_match_all('/\[[^\]]*\]/', $pgn, $tags);
    foreach ($tags[0] as $tag) {
      $pair = [];
      if (!preg_match('/\[\s*(\S+)\s+"(.*)"\s*\]/', $tag, $pair)) {
        continue;
      }
      $this->set_tag($pair[1], $pair[2]);
    }
  }

  /**
  * Removes tag pair section, all comments, move number indicators and game termination marker.
  * What remains is the plain list of moves separated by spaces.
  */
  private function get_movetext_section(string $pgn) : string
  {
    // remove all tags
    $pgn = preg_replace('/\[[^\]]*\]/', ' ', $pgn);

    // remove all comments
    $pgn = preg_replace('/{[^}]*}/', ' ', $pgn);

    // remove all move number indicators
    $pgn = preg_replace('/[0-9]+\.+/', ' ', $pgn);

    // remove excessive whitespaces
    $pgn = preg_replace('/\s+/', ' ', $pgn);

    // remove leading and trailing whitespace
    $pgn = trim($pgn);

    // remove game termination markers
    $pgn = preg_replace('/0-1$/', '', $pgn);
    $pgn = preg_replace('/1-0$/', '', $pgn);
    $pgn = preg_replace('#1/2-1/2$#', '', $pgn);
    $pgn = preg_replace('/\*$/', '', $pgn);

    $pgn = trim($pgn);

    return $pgn;
  }



}

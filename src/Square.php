<?php

namespace Onspli\Chess;


/**
* Class representing coordinates of a square on a chess board.
*
* Square can be on of the following types:
*  - regular square - 'e4'
*  - file - 'e' for e-file
*  - rank - '4' for 4th rank
*  - null square - '-', '', square not on a board
*
*/
class Square
{
  private $rank_index;
  private $file_index;

  /**
  * Create square.
  *
  * Constructor accepts either SAN (standard algebraic notation) string,
  * or file and rank indexes.
  *  - regular square: `new Square('e4'); new Square(4, 3);`
  *  - null square: `new Square; new Square('-'); new Square(null, null);`
  *  - file: `new Square('e'); new Square(4, null);`
  *  - rank: `new Square('4'); new Square(null, 3);`
  *
  * Throws `ParseException` if SAN is invalid or if indices are not integers.
  * Creates null square if file or rank index is out of bounds.
  */
  function __construct($san_or_file_index = null, $rank_index = null)
  {
    if (is_string($san_or_file_index)) {
      $this->parse_san($san_or_file_index);
    } else {
      $this->file_index = $san_or_file_index;
      $this->rank_index = $rank_index;
      $this->validate_indices();
    }
  }

  /**
  * Get rank index of the square.
  *
  * For square 'e4' it returns 3. Throws `\OutOfBoundsException` for null squares
  * and files.
  */
  public function get_rank_index() : int
  {
    if ($this->is_null() || $this->is_file()) {
      throw new \OutOfBoundsException;
    }
    return $this->rank_index;
  }

  /**
  * Get rank of the square.
  *
  * For square 'e4' it returns '4'. Throws `\OutOfBoundsException` for null squares.
  * Returns empty string for files.
  */
  public function get_rank() : string
  {
    if ($this->is_null()) {
      throw new \OutOfBoundsException;
    }
    if ($this->is_file()) {
      return '';
    }
    return $this->rank_index + 1;
  }

  /**
  * Get file index of the square.
  *
  * For square 'e4' it returns 4. Throws `\OutOfBoundsException` for null squares
  * and ranks.
  */
  public function get_file_index() : int
  {
    if ($this->is_null() || $this->is_rank()) {
      throw new \OutOfBoundsException;
    }
    return $this->file_index;
  }

  /**
  * Get file of the square.
  *
  * For square 'e4' it returns 'e'. Throws `\OutOfBoundsException` for null squares.
  * Returns empty string for ranks.
  */
  public function get_file() : string
  {
    if ($this->is_null()) {
      throw new \OutOfBoundsException;
    }
    if ($this->is_rank()) {
      return '';
    }
    return chr(ord('a') + $this->file_index);
  }

  /**
  * Returns SAN (standard algebraic notation) string.
  *
  * For
  * - regular square - 'e4'
  * - file - 'e'
  * - rank - '4',
  * - null square - '-'
  */
  public function export() : string
  {
    if ($this->is_null()) {
      return '-';
    }

    return $this->get_file() . $this->get_rank();
  }

  /**
  * Check wether square is null.
  */
  public function is_null() : bool
  {
    return $this->rank_index === null && $this->file_index === null;
  }

  /**
  * Check wether square is rank.
  */
  public function is_rank() : bool
  {
    return $this->rank_index !== null && $this->file_index === null;
  }

  /**
  * Check wether square has rank.
  *
  * Regular squares and ranks has rank.
  */
  public function has_rank() : bool
  {
    return $this->rank_index !== null;
  }

  /**
  * Check wether square is file.
  */
  public function is_file() : bool
  {
    return $this->rank_index === null && $this->file_index !== null;
  }

  /**
  * Check wether square has file.
  *
  * Regular squares and files has file.
  */
  public function has_file() : bool
  {
    return $this->file_index !== null;
  }

  /**
  * Check wether square is regular square.
  */
  public function is_regular() : bool
  {
    return $this->has_rank() && $this->has_file();
  }

  /**
  * Get square with relative position to this square.
  *
  * Throws `\OutOfBoundsException` when trying to get relative square of a non
  * regular squares. Returns null square if relative coordinates are outside
  * the board.
  */
  public function relative(int $east, int $north)
  {
    if (!$this->is_regular()) {
      throw new \OutOfBoundsException;
    }
    return new Square($this->get_file_index() + $east, $this->get_rank_index() + $north);
  }

  /**
  * Add square to the end of array.
  *
  * Method ignores nonregular squares.
  * @param bool $as_object Add square as an object rather than SAN string.
  */
  public function push_to_array(array &$array, bool $as_object = false) : void
  {
    if (!$this->is_regular()) {
      return;
    }
    if ($as_object) {
      $array[] = $this;
    } else {
      $array[] = $this->export();
    }
  }

  private function set_to_null() : void
  {
    $this->file_index = null;
    $this->rank_index = null;
  }

  private static function is_index_valid($index) : bool
  {
    return $index === null || intval($index) === $index;
  }

  private static function is_index_in_range($index) : bool
  {
    return $index >= 0 && $index <= 7;
  }

  private function validate_indices() : void
  {
    if (!self::is_index_valid($this->file_index) || !self::is_index_valid($this->rank_index)) {
      throw new ParseException;
    }
    if (!self::is_index_in_range($this->file_index) || !self::is_index_in_range($this->rank_index)) {
      $this->set_to_null();
    }
  }

  private function parse_san(string $san) : void
  {
    if ($san == '-' || $san == '') {
      $this->set_to_null();
      return;
    }
    $matches = [];
    if (!preg_match('/^([a-h]?)([1-8]?)$/', $san, $matches)) {
      throw new ParseException;
    }
    if ($matches[1]) {
      $this->file_index = ord($matches[1]) - ord('a');
    }
    if ($matches[2]) {
      $this->rank_index = intval($matches[2]) - 1;
    }
  }

}

<?php

namespace Onspli\Chess;

/**
* Class for parsing moves in SAN (standard algebraic notation).
*/
class Move
{

  private $origin;
  private $target;
  private $piece;
  private $capture = '';
  private $castling = '';
  private $promotion = '';
  private $check_mate = '';
  private $annotation = '';

  function __construct(string $move)
  {
    $matches = array();

    if (preg_match('/^([O-]+)([+#]?)([!?]*)$/', $move, $matches)) {
      $castling = $matches[1];
      if ($castling != 'O-O' && $castling != 'O-O-O') {
        throw new ParseException;
      }
      $this->piece = 'K';
      $this->castling = $castling;
      $this->origin = new Square;
      $this->target = new Square;
      $this->check_mate = $matches[2];
      $this->annotation = $matches[3];
      return;
    }

    if (!preg_match('/^([PNBRQK]?)([a-h]?[1-8]?)([x]?)([a-h][1-8])(?:=([NBRQ]))?([+#]?)([!?]*)$/', $move, $matches)) {
      throw new ParseException;
    }

    $this->piece = $matches[1];
    $this->origin = new Square($matches[2]);
    $this->capture = $matches[3];
    $this->target = new Square($matches[4]);
    $this->promotion = $matches[5];
    $this->check_mate = $matches[6];
    $this->annotation = $matches[7];

    self::validate_promotion();
  }

  private function validate_promotion() : void
  {
    if ($this->get_promotion() && $this->get_piece() != 'P') {
      throw new RulesException;
    }

    if ($this->get_promotion() && $this->target->get_rank_index() != 7 && $this->target->get_rank_index() != 0) {
      throw new RulesException;
    }

    if (!$this->get_promotion() && $this->get_piece() == 'P' && ($this->target->get_rank_index() == 7 || $this->target->get_rank_index() == 0)) {
      throw new RulesException;
    }
  }

  public function export() : string
  {
    $str = '';
    if ($this->get_castling()) {
      $str .= $this->get_castling();
    } else {
      $str .= $this->san_piece();
      $str .= $this->san_origin();
      $str .= $this->capture;
      $str .= $this->get_target();
      $str .= $this->san_promotion();
    }
    $str .= $this->san_extension();
    return $str;
  }


  private function san_origin() : string
  {
    if (!$this->origin->is_null()) {
      return $this->origin->export();
    }
    return '';
  }

  private function san_piece() : string
  {
    if ($this->get_piece() != 'P') {
      return $this->get_piece();
    }
    return '';
  }

  private function san_extension() : string
  {
    return $this->get_check_mate() . $this->get_annotation();
  }

  private function san_promotion() : string
  {
    if ($this->get_promotion()) {
      return '=' . $this->get_promotion();
    }
    return '';
  }

  public function get_capture() : bool
  {
    return $this->capture;
  }

  public function get_target(bool $as_object = false)
  {
    if ($as_object) {
      return $this->target;
    } else {
      return $this->target->export();
    }
  }

  public function get_origin(bool $as_object = false)
  {
    if ($as_object) {
      return $this->origin;
    } else {
      return $this->origin->export();
    }
  }

  public function set_origin($square) : void
  {
    if (is_string($square)) {
      $square = new Square($square);
    }
    $this->origin = $square;
  }

  public function get_piece() : string
  {
    if ($this->piece == '') {
      return 'P';
    }
    return $this->piece;
  }

  public function get_castling() : string
  {
    return $this->castling;
  }

  public function get_promotion() : string
  {
    return $this->promotion;
  }

  public function get_check_mate() : string
  {
    return $this->check_mate;
  }

  public function get_annotation() : string
  {
    return $this->annotation;
  }

}

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
  private $capture = false;
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

    $piece = $matches[1];
    if (!$piece) {
      $piece = 'P';
    }
    $this->piece = $piece;
    $this->origin = new Square($matches[2] ? $matches[2] : '-');
    $this->capture = ($matches[3] == 'x');
    $this->target = new Square($matches[4]);
    $this->promotion = $matches[5];
    $this->check_mate = $matches[6];
    $this->annotation = $matches[7];

    if ($this->promotion() && $this->piece() != 'P') {
      throw new RulesException;
    }

    if ($this->promotion() && $this->target->rank_index() != 7 && $this->target->rank_index() != 0) {
      throw new RulesException;
    }

    if (!$this->promotion() && $this->piece() == 'P' && ($this->target->rank_index() == 7 || $this->target->rank_index() == 0)) {
      throw new RulesException;
    }

  }

  public function san() : string
  {
    $str = '';
    if ($this->castling()) {
      $str .= $this->castling();
    } else {
      $str .= $this->san_piece();
      $str .= $this->san_origin();
      $str .= $this->san_capture();
      $str .= $this->target();
      $str .= $this->san_promotion();
    }
    $str .= $this->san_extension();
    return $str;
  }


  private function san_origin() : string
  {
    if (!$this->origin->is_null()) {
      return $this->origin->san();
    }
    return '';
  }

  private function san_piece() : string
  {
    if ($this->piece() != 'P') {
      return $this->piece();
    }
    return '';
  }

  private function san_capture() : string
  {
    if ($this->capture()) {
      return 'x';
    }
    return '';
  }

  private function san_extension() : string
  {
    return $this->check_mate() . $this->annotation();
  }

  private function san_promotion() : string
  {
    if ($this->promotion()) {
      return '=' . $this->promotion();
    }
    return '';
  }

  public function capture() : bool
  {
    return $this->capture;
  }

  public function target(bool $as_object = false)
  {
    if ($as_object) {
      return $this->target;
    } else {
      return $this->target->san();
    }
  }

  public function origin(bool $as_object = false)
  {
    if ($as_object) {
      return $this->origin;
    } else {
      return $this->origin->san();
    }
  }

  public function piece() : string
  {
    return $this->piece;
  }

  public function castling() : string
  {
    return $this->castling;
  }

  public function promotion() : string
  {
    return $this->promotion;
  }

  public function check_mate() : string
  {
    return $this->check_mate;
  }

  public function annotation() : string
  {
    return $this->annotation;
  }

}

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
      if ($this->piece() != 'P') {
        $str .= $this->piece();
      }
      if (!$this->origin->is_null()) {
        $str .= $this->origin->san();
      }
      if ($this->capture()) {
        $str .= 'x';
      }
      $str .= $this->target();
      if ($this->promotion()) {
        $str .= '=' . $this->promotion();
      }
    }
    $str .= $this->check_mate();
    $str .= $this->annotation();
    return $str;
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

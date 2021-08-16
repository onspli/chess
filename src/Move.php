<?php

namespace Onspli\Chess;

class Move
{

  private $origin_rank = null;
  private $origin_file = null;
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

    if (preg_match('/([O-]+)([+#]?)([!?]*)/', $move, $matches)) {
      $castling = $matches[1];
      if ($castling != 'O-O' && $castling != 'O-O-O') {
        throw new ParseException;
      }
      $this->piece = 'K';
      $this->castling = $castling;
      $this->target = new Square;
      $this->check_mate = $matches[2];
      $this->annotation = $matches[3];
      return;
    }

    if (!preg_match('/([PNBRQK]?)([a-h]?)([1-8]?)([x]?)([a-h][1-8])(?:=([NBRQ]))?([+#]?)([!?]*)/', $move, $matches)) {
      throw new ParseException;
    }

    $piece = $matches[1];
    if (!$piece) {
      $piece = 'P';
    }

    $this->piece = $piece;
    $this->capture = ($matches[4] == 'x');
    $this->target = new Square($matches[5]);
    $this->promotion = $matches[6];
    $this->check_mate = $matches[7];
    $this->annotation = $matches[8];

    $file = $matches[2];
    if ($file === '') {
      $this->origin_file = null;
    } else {
      $this->origin_file = ord($file[0]) - ord('a');
    }

    $rank = $matches[3];
    if ($rank === '') {
      $this->origin_rank = null;
    } else {
      $this->origin_rank = intval($rank) - 1;
    }


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
      $str .= $this->origin_file();
      $str .= $this->origin_rank();
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

  public function origin_file(bool $as_index = false)
  {
    if ($as_index) {
      return $this->origin_file;
    }
    if ($this->origin_file === null) {
      return '';
    }
    return chr(ord('a') + $this->origin_file);
  }

  public function origin_rank(bool $as_index = false)
  {
    if ($as_index) {
      return $this->origin_rank;
    }
    if ($this->origin_rank === null) {
      return '';
    }
    return $this->origin_rank + 1;
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

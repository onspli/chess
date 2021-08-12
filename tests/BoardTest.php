<?php
namespace Onspli\Chess;
use PHPUnit\Framework\TestCase;
use Onspli\Chess;

/**
 * @covers Onspli\Chess\Board
 */
final class BoardTest extends TestCase
{

  public function testInitialization() : void
  {
    $board = new Board;
    $this->assertEquals('', $board->square('e4'));
    $board->set_square('e4', 'P');
    $this->assertEquals('P', $board->square('e4'));
  }

}

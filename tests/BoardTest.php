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

  public function testNothrow() : void
  {
    $board = new Board;
    $this->assertNull($board->set_square_nothrow('xx', 'p'));
    $this->assertEquals('', $board->square_nothrow('xx'));
  }

  public function testFind() : void
  {
    $board = new Board;
    $board->set_square('e5', 'N');
    $board->set_square('a2', 'P');
    $board->set_square('e4', 'P');

    $res = $board->find('P');
    $this->assertEquals([new Square('a2'), new Square('e4')], $res);

    $res = $board->find('N');
    $this->assertEquals([new Square('e5')], $res);

    $res = $board->find('Q');
    $this->assertEquals([], $res);

    $res = $board->find('');
    $this->assertEquals(61, sizeof($res));
  }

}

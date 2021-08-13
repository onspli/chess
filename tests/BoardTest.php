<?php
namespace Onspli\Chess;
use PHPUnit\Framework\TestCase;
use Onspli\Chess;

/**
 * @covers Onspli\Chess\Board
 */
final class BoardTest extends TestCase
{

  public function testInitializationAndExport() : void
  {
    $board = new Board;
    $this->assertEquals('', $board->square('e4'));
    $board->set_square('e4', 'P');
    $this->assertEquals('P', $board->square('e4'));

    $board = new Board;
    $this->assertEquals('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR', $board->export());

    $board = new Board('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR');
    $this->assertEquals('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR', $board->export());
  }

  public function testNothrow() : void
  {
    $board = new Board;
    $this->assertNull($board->set_square_nothrow('xx', 'p'));
    $this->assertEquals('', $board->square_nothrow('xx'));
  }


  public function testFind() : void
  {
    $board = new Board('8/8/8/8/8/8/8/8');
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

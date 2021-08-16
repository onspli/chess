<?php
namespace Onspli\Chess;
use PHPUnit\Framework\TestCase;

/**
 * @covers Onspli\Chess\Move
 */
final class MoveTest extends TestCase
{

  public function testGetters() : void
  {

    $move = new Move('e4');
    $this->assertEquals('', $move->castling());
    $this->assertEquals('P', $move->piece());
    $this->assertEquals('e4', $move->target());
    $this->assertEquals('e4', $move->target(true)->san());
    $this->assertEquals('', $move->origin_file());
    $this->assertEquals('', $move->origin_rank());
    $this->assertNull($move->origin_file(true));
    $this->assertNull($move->origin_rank(true));
    $this->assertEquals('', $move->check_mate());
    $this->assertEquals('', $move->annotation());
    $this->assertFalse($move->capture());

    $move = new Move('Nxe4');
    $this->assertEquals('N', $move->piece());
    $this->assertEquals('e4', $move->target());
    $this->assertEquals('', $move->origin_file());
    $this->assertEquals('', $move->origin_rank());
    $this->assertTrue($move->capture());

    $move = new Move('Rae1');
    $this->assertEquals('R', $move->piece());
    $this->assertEquals('e1', $move->target());
    $this->assertEquals('a', $move->origin_file());
    $this->assertEquals('', $move->origin_rank());
    $this->assertFalse($move->capture());

    $move = new Move('N3e1');
    $this->assertEquals('N', $move->piece());
    $this->assertEquals('e1', $move->target());
    $this->assertEquals('', $move->origin_file());
    $this->assertEquals('3', $move->origin_rank());
    $this->assertFalse($move->capture());

    $move = new Move('Qa3xe1');
    $this->assertEquals('Q', $move->piece());
    $this->assertEquals('e1', $move->target());
    $this->assertEquals('a', $move->origin_file());
    $this->assertEquals('3', $move->origin_rank());
    $this->assertEquals(0, $move->origin_file(true));
    $this->assertEquals(2, $move->origin_rank(true));
    $this->assertTrue($move->capture());

    $move = new Move('e4+?!');
    $this->assertEquals('+', $move->check_mate());
    $this->assertEquals('?!', $move->annotation());

    $move = new Move('O-O');
    $this->assertEquals('O-O', $move->castling());
    $this->assertEquals('K', $move->piece());
    $this->assertEquals('-', $move->target());
    $this->assertEquals('', $move->origin_file());
    $this->assertEquals('', $move->origin_rank());
    $this->assertFalse($move->capture());

    $move = new Move('O-O-O');
    $this->assertEquals('O-O-O', $move->castling());
    $this->assertEquals('K', $move->piece());
    $this->assertEquals('-', $move->target());
    $this->assertEquals('', $move->origin_file());
    $this->assertEquals('', $move->origin_rank());
    $this->assertFalse($move->capture());

    $move = new Move('O-O#!!');
    $this->assertEquals('O-O', $move->castling());
    $this->assertEquals('#', $move->check_mate());
    $this->assertEquals('!!', $move->annotation());

  }

  public function testExport() : void
  {
      $this->assertEquals('e4', (new Move('e4'))->san());
      $this->assertEquals('Nxe4', (new Move('Nxe4'))->san());
      $this->assertEquals('Rae1', (new Move('Rae1'))->san());
      $this->assertEquals('N3e1', (new Move('N3e1'))->san());
      $this->assertEquals('Qa3xe1', (new Move('Qa3xe1'))->san());
      $this->assertEquals('a8=Q', (new Move('a8=Q'))->san());
      $this->assertEquals('a7+', (new Move('a7+'))->san());
      $this->assertEquals('Nf5!?', (new Move('Nf5!?'))->san());
      $this->assertEquals('O-O', (new Move('O-O'))->san());
      $this->assertEquals('O-O-O', (new Move('O-O-O'))->san());
  }

  public function testIncompleteTarget() : void
  {
    $this->expectException(ParseException::class);
    new Move('Nxe');
  }

  public function testPromotionOfKnight() : void
  {
    $this->expectException(RulesException::class);
    new Move('Na8=Q');
  }

  public function testPromotionOnSeventhRank() : void
  {
    $this->expectException(RulesException::class);
    new Move('a7=Q');
  }

  public function testPromotionToPawn() : void
  {
    $this->expectException(RulesException::class);
    new Move('a8=P');
  }

  public function testNoPromotionOnLastRank() : void
  {
    $this->expectException(RulesException::class);
    new Move('a8');
  }

  public function testMalformedCastling() : void
  {
    $this->expectException(ParseException::class);
    new Move('O--O');
  }



}

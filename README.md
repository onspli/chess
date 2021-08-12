# onspli/chess
PHP library for parsing chess games in PGN and FEN formats

![build](https://github.com/onspli/chess/actions/workflows/build.yml/badge.svg) ![license](https://img.shields.io/github/license/onspli/chess)

## Installation
Install with composer:
```
composer require onspli/chess
```

## Usage

Example 1:
```php
use Onspli\Chess;
$fen = Chess\FEN;
echo($fen->export());     // rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1
echo($fen->board());      // rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR
echo($fen->active());     // w
echo($fen->castling());   // KQkq
echo($fen->en_passant()); // -
echo($fen->halfmove());   // 0
echo($fen->fullmove());   // 1
```
Example 2:
```php
use Onspli\Chess;
$fen = Chess\FEN('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR b KQq c6 1 2');
echo($fen->export());     // rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR b KQq c6 1 2
echo($fen->board());      // rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR
echo($fen->active());     // b
echo($fen->castling());   // KQq
echo($fen->en_passant()); // c6
echo($fen->halfmove());   // 1
echo($fen->fullmove());   // 2
```

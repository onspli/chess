# onspli/chess
PHP library for parsing chess games in PGN and FEN formats

![build](https://github.com/onspli/chess/actions/workflows/build.yml/badge.svg) [![coverage](https://coveralls.io/repos/github/onspli/chess/badge.svg?branch=master)](https://coveralls.io/github/onspli/chess?branch=master) [![license](https://img.shields.io/github/license/onspli/chess?label=license)](https://github.com/onspli/chess/blob/master/LICENSE)

## Installation
Install with composer:
```
composer require onspli/chess
```

## Usage

Setup chess board to starting position and read FEN fields.
```php
use Onspli\Chess;
$fen = new Chess\FEN;
echo($fen->export());     // rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1
echo($fen->board());      // rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR
echo($fen->active());     // w
echo($fen->castling());   // KQkq
echo($fen->en_passant()); // -
echo($fen->halfmove());   // 0
echo($fen->fullmove());   // 1
```

Initialize custom position and read FEN fields.
```php
use Onspli\Chess;
$fen = new Chess\FEN('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR b KQq c6 1 2');
echo($fen->export());     // rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR b KQq c6 1 2
echo($fen->board());      // rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR
echo($fen->active());     // b
echo($fen->castling());   // KQq
echo($fen->en_passant()); // c6
echo($fen->halfmove());   // 1
echo($fen->fullmove());   // 2
```

Each of the fields can be set with the corresponding setter:
```php
use Onspli\Chess;
$fen = new Chess\FEN;
echo($fen->export());     // rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1

$fen->set_board('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR');
$fen->set_active('b');
$fen->set_castling('KQq');
$fen->set_en_passant('c6');
$fen->set_halfmove(1);
$fen->set_fullmove(2);
echo($fen->export());     // rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR b KQq c6 1 2
```

# onspli/chess
PHP library for parsing chess games in PGN and FEN formats

![build](https://github.com/onspli/chess/actions/workflows/build.yml/badge.svg) [![coverage](https://coveralls.io/repos/github/onspli/chess/badge.svg?branch=master)](https://coveralls.io/github/onspli/chess?branch=master) [![license](https://img.shields.io/github/license/onspli/chess?label=license)](https://github.com/onspli/chess/blob/master/LICENSE) [![last commit](https://img.shields.io/github/last-commit/onspli/chess)](https://github.com/onspli/chess)

## Installation
Install with composer:
```
composer require onspli/chess
```

## Usage

Setup chess board to starting position and read FEN fields.
``` php
$fen = new Onspli\Chess\FEN;
echo($fen->export());
echo($fen->board());
echo($fen->active());
echo($fen->castling());
echo($fen->en_passant());
echo($fen->halfmove());
echo($fen->fullmove());
echo($fen->preview());
```

Initialize custom position and read FEN fields.
``` php
$fen = new Onspli\Chess\FEN('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR b KQq c6 1 2');
echo($fen->export());
echo($fen->board());
echo($fen->active());
echo($fen->castling());
echo($fen->en_passant());
echo($fen->halfmove());
echo($fen->fullmove());
echo($fen->preview());
```

Each of the fields can be set with the corresponding setter:
``` php
$fen = new Onspli\Chess\FEN;
echo($fen->export());

$fen->set_board('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR');
$fen->set_active('b');
$fen->set_castling('KQq');
$fen->set_en_passant('c6');
$fen->set_halfmove(1);
$fen->set_fullmove(2);
echo($fen->export());
echo($fen->preview());
```

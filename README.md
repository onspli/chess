# onspli/chess
PHP library for parsing chess games in PGN and FEN formats

![build](https://github.com/onspli/chess/actions/workflows/build.yml/badge.svg) [![coverage](https://coveralls.io/repos/github/onspli/chess/badge.svg?branch=master)](https://coveralls.io/github/onspli/chess?branch=master) [![license](https://img.shields.io/github/license/onspli/chess?label=license)](https://github.com/onspli/chess/blob/master/LICENSE) [![last commit](https://img.shields.io/github/last-commit/onspli/chess)](https://github.com/onspli/chess)

## Installation
Install with composer:
```
composer require onspli/chess
```

## Usage

Read complete auto-generated [documentation](docs) or learn from the following examples.

Setup chess board to starting position and read FEN fields.
``` php
$fen = new Onspli\Chess\FEN;
echo($fen->export());
// rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1
echo($fen->board());
// rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR
echo($fen->active());
// w
echo($fen->castling());
// KQkq
echo($fen->en_passant());
// -
echo($fen->halfmove());
// 0
echo($fen->fullmove());
// 1
echo($fen->preview());
/*
rnbqkbnr
pppppppp
........
........
........
........
PPPPPPPP
RNBQKBNR
*/
```

Initialize custom position and read FEN fields.
``` php
$fen = new Onspli\Chess\FEN('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR b KQq c6 1 2');
echo($fen->export());
// rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR b KQq c6 1 2
echo($fen->board());
// rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR
echo($fen->active());
// b
echo($fen->castling());
// KQq
echo($fen->en_passant());
// c6
echo($fen->halfmove());
// 1
echo($fen->fullmove());
// 2
echo($fen->preview());
/*
rnbqkbnr
pp.ppppp
........
..p.....
....P...
........
PPPP.PPP
RNBQKBNR
*/
```

Each of the fields can be set with the corresponding setter:
``` php
$fen = new Onspli\Chess\FEN;
echo($fen->export());
// rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1

$fen->set_board('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR');
$fen->set_active('b');
$fen->set_castling('KQq');
$fen->set_en_passant('c6');
$fen->set_halfmove(1);
$fen->set_fullmove(2);
echo($fen->export());
// rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR b KQq c6 1 2
echo($fen->preview());
/*
rnbqkbnr
pp.ppppp
........
..p.....
....P...
........
PPPP.PPP
RNBQKBNR
*/
```

Perform moves:
``` php
$fen = new Onspli\Chess\FEN;
echo($fen->preview());
/*
rnbqkbnr
pppppppp
........
........
........
........
PPPPPPPP
RNBQKBNR
*/
$fen->move('e4');
$fen->move('g6');
$fen->move('Nf3');
$fen->move('Nf6');
$fen->move('Bc4');
$fen->move('Bg7');
$fen->move('O-O');
$fen->move('O-O');
echo($fen->preview());
/*
rnbq.rk.
ppppppbp
.....np.
........
..B.P...
.....N..
PPPP.PPP
RNBQ.RK.
*/
echo($fen->export());
// rnbq1rk1/ppppppbp/5np1/8/2B1P3/5N2/PPPP1PPP/RNBQ1RK1 w - - 6 5
```

Test check:
``` php
$fen = new Onspli\Chess\FEN;
$fen->set_active('w');
$fen->set_board('1q5k/8/8/8/8/8/8/K7');
echo($fen->preview());
/*
.q.....k
........
........
........
........
........
........
K.......
*/
echo($fen->is_check() ? 'true' : 'false');
// false
$fen->move('Ka2');
$fen->move('Qa8');
echo($fen->preview());
/*
q......k
........
........
........
........
........
K.......
........
*/
echo($fen->is_check() ? 'true' : 'false');
// true
```

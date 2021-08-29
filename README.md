# onspli/chess
PHP library for parsing chess games in PGN and FEN formats

![build](https://github.com/onspli/chess/actions/workflows/build.yml/badge.svg) [![license](https://img.shields.io/github/license/onspli/chess?label=license)](https://github.com/onspli/chess/blob/master/LICENSE) [![coverage](https://coveralls.io/repos/github/onspli/chess/badge.svg?branch=master)](https://coveralls.io/github/onspli/chess?branch=master) [![maintainability](https://api.codeclimate.com/v1/badges/4c2f7aaf563a1f492c21/maintainability)](https://codeclimate.com/github/onspli/chess/maintainability) [![last commit](https://img.shields.io/github/last-commit/onspli/chess)](https://github.com/onspli/chess)

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
echo($fen->get_board());
// rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR
echo($fen->get_active_color());
// w
echo($fen->get_castling_string());
// KQkq
echo($fen->get_en_passant());
// -
echo($fen->get_halfmove());
// 0
echo($fen->get_fullmove());
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
echo($fen->get_board());
// rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR
echo($fen->get_active_color());
// b
echo($fen->get_castling_string());
// KQq
echo($fen->get_en_passant());
// c6
echo($fen->get_halfmove());
// 1
echo($fen->get_fullmove());
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
$fen->set_active_color('b');
$fen->set_castling_string('KQq');
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

Test check, mate, stalemate:
``` php
$fen = new Onspli\Chess\FEN;
$fen->set_active_color('w');
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
echo($fen->is_stalemate() ? 'true' : 'false');
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
echo($fen->is_mate() ? 'true' : 'false');
// false
```

List all possible moves:
``` php
$fen = new Onspli\Chess\FEN;
$fen->move('e4');
$fen->move('g6');
echo($fen->preview());
/*
rnbqkbnr
pppppp.p
......p.
........
....P...
........
PPPP.PPP
RNBQKBNR
*/
print_r($fen->get_possible_moves());
/*
Array
(
    [0] => Ba6
    [1] => Bb5
    [2] => Bc4
    [3] => Bd3
    [4] => Be2
    [5] => Ke2
    [6] => Na3
    [7] => Nc3
    [8] => Ne2
    [9] => Nf3
    [10] => Nh3
    [11] => Qe2
    [12] => Qf3
    [13] => Qg4
    [14] => Qh5
    [15] => a3
    [16] => a4
    [17] => b3
    [18] => b4
    [19] => c3
    [20] => c4
    [21] => d3
    [22] => d4
    [23] => e5
    [24] => f3
    [25] => f4
    [26] => g3
    [27] => g4
    [28] => h3
    [29] => h4
)

*/
```

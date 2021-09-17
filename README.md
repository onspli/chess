# onspli/chess
PHP library for reading and editing FEN and PGN chess formats.

![build](https://github.com/onspli/chess/actions/workflows/build.yml/badge.svg) [![license](https://img.shields.io/github/license/onspli/chess?label=license)](https://github.com/onspli/chess/blob/master/LICENSE) [![coverage](https://coveralls.io/repos/github/onspli/chess/badge.svg?branch=master)](https://coveralls.io/github/onspli/chess?branch=master) [![maintainability](https://api.codeclimate.com/v1/badges/4c2f7aaf563a1f492c21/maintainability)](https://codeclimate.com/github/onspli/chess/maintainability) [![last commit](https://img.shields.io/github/last-commit/onspli/chess)](https://github.com/onspli/chess)

## Features
### FEN class
 - load FEN representing chess position
 - read and modify all FEN fields
 - export FEN
 - read and set piece placement
 - get or set piece on any square
 - test position for check, mate, stalemate
 - test position for fifty-move rule
 - perform move in given position
 - test if move is legal in given position
 - list all legal moves in given position

### PGN class
 - load PGN representing chess game
 - read and set all tag pairs (PGN headers)
 - export PGN
 - read and add moves
 - get FEN position after any move

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
print_r($fen->get_legal_moves());
/*
Array
(
    [0] => a4
    [1] => a3
    [2] => b4
    [3] => b3
    [4] => c4
    [5] => c3
    [6] => d4
    [7] => d3
    [8] => f4
    [9] => f3
    [10] => g4
    [11] => g3
    [12] => h4
    [13] => h3
    [14] => e5
    [15] => Nc3
    [16] => Na3
    [17] => Ne2
    [18] => Nh3
    [19] => Nf3
    [20] => Be2
    [21] => Bd3
    [22] => Bc4
    [23] => Bb5
    [24] => Ba6
    [25] => Qe2
    [26] => Qf3
    [27] => Qg4
    [28] => Qh5
    [29] => Ke2
)

*/
```

Load game in PGN notation:
``` php
$pgn = new Onspli\Chess\PGN('[Event "Testing"] 1.Nf3 Nf6 2.c4 g6');
echo($pgn->get_tag('Event'));
// Testing
echo($pgn->get_current_fen(true)->preview());
/*
rnbqkb.r
pppppp.p
.....np.
........
..P.....
.....N..
PP.PPPPP
RNBQKB.R
*/
echo($pgn->get_fen_after_move(1, 'b', true)->preview());
/*
rnbqkb.r
pppppppp
.....n..
........
........
.....N..
PPPPPPPP
RNBQKB.R
*/

echo($pgn->get_move(2, 'w'));
// c4
echo($pgn->get_halfmove(3));
// c4
echo($pgn->get_current_halfmove_number());
// 4

echo($pgn->get_fen_after_move(1, 'b'));
// rnbqkb1r/pppppppp/5n2/8/8/5N2/PPPPPPPP/RNBQKB1R w KQkq - 2 2
echo($pgn->get_fen_after_halfmove(2));
// rnbqkb1r/pppppppp/5n2/8/8/5N2/PPPPPPPP/RNBQKB1R w KQkq - 2 2
echo($pgn->get_initial_fen());
// rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1

$pgn->move('a4');
$pgn->set_tag('Site', 'Github');
echo($pgn->export());
/*
[Event "Testing"]
[Site "Github"]
1. Nf3 Nf6 2. c4 g6 3. a4
*/

for ($hm = $pgn->get_initial_halfmove_number() - 1; $hm <= $pgn->get_current_halfmove_number(); $hm++) echo $pgn->get_fen_after_halfmove($hm) . PHP_EOL;
/*
rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1
rnbqkbnr/pppppppp/8/8/8/5N2/PPPPPPPP/RNBQKB1R b KQkq - 1 1
rnbqkb1r/pppppppp/5n2/8/8/5N2/PPPPPPPP/RNBQKB1R w KQkq - 2 2
rnbqkb1r/pppppppp/5n2/8/2P5/5N2/PP1PPPPP/RNBQKB1R b KQkq c3 0 2
rnbqkb1r/pppppp1p/5np1/8/2P5/5N2/PP1PPPPP/RNBQKB1R w KQkq - 0 3
rnbqkb1r/pppppp1p/5np1/8/P1P5/5N2/1P1PPPPP/RNBQKB1R b KQkq a3 0 3

*/
```

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
echo($fen->get_board());
echo($fen->get_active_color());
echo($fen->get_castling_string());
echo($fen->get_en_passant());
echo($fen->get_halfmove());
echo($fen->get_fullmove());
echo($fen->preview());
```

Initialize custom position and read FEN fields.
``` php
$fen = new Onspli\Chess\FEN('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR b KQq c6 1 2');
echo($fen->export());
echo($fen->get_board());
echo($fen->get_active_color());
echo($fen->get_castling_string());
echo($fen->get_en_passant());
echo($fen->get_halfmove());
echo($fen->get_fullmove());
echo($fen->preview());
```

Each of the fields can be set with the corresponding setter:
``` php
$fen = new Onspli\Chess\FEN;
echo($fen->export());

$fen->set_board('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR');
$fen->set_active_color('b');
$fen->set_castling_string('KQq');
$fen->set_en_passant('c6');
$fen->set_halfmove(1);
$fen->set_fullmove(2);
echo($fen->export());
echo($fen->preview());
```

Perform moves:
``` php
$fen = new Onspli\Chess\FEN;
echo($fen->preview());
$fen->move('e4');
$fen->move('g6');
$fen->move('Nf3');
$fen->move('Nf6');
$fen->move('Bc4');
$fen->move('Bg7');
$fen->move('O-O');
$fen->move('O-O');
echo($fen->preview());
echo($fen->export());
```

Test check, mate, stalemate:
``` php
$fen = new Onspli\Chess\FEN;
$fen->set_active_color('w');
$fen->set_board('1q5k/8/8/8/8/8/8/K7');
echo($fen->preview());
echo($fen->is_check() ? 'true' : 'false');
echo($fen->is_stalemate() ? 'true' : 'false');
$fen->move('Ka2');
$fen->move('Qa8');
echo($fen->preview());
echo($fen->is_check() ? 'true' : 'false');
echo($fen->is_mate() ? 'true' : 'false');
```

List all possible moves:
``` php
$fen = new Onspli\Chess\FEN;
$fen->move('e4');
$fen->move('g6');
echo($fen->preview());
print_r($fen->get_legal_moves());
```

Load game in PGN notation:
``` php
$pgn = new Onspli\Chess\PGN('[Event "Testing"] 1.Nf3 Nf6 2.c4 g6');
echo($pgn->get_tag('Event'));
echo($pgn->get_current_fen(true)->preview());
echo($pgn->get_fen_after_halfmove(2, true)->preview());

echo($pgn->get_halfmove(Onspli\Chess\PGN::get_halfmove_number(2, 'w')));
echo($pgn->get_halfmove(3));
echo($pgn->get_current_halfmove_number());

echo($pgn->get_fen_after_halfmove(2));
echo($pgn->get_initial_fen());

$pgn->move('a4');
$pgn->set_tag('Site', 'Github');
echo($pgn->export());

for ($hm = $pgn->get_initial_halfmove_number() - 1; $hm <= $pgn->get_current_halfmove_number(); $hm++) echo $pgn->get_fen_after_halfmove($hm) . PHP_EOL;
```

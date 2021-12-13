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
echo($fen->export_short());
echo($fen->get_board());
echo($fen->get_active_color());
echo($fen->get_castling());
echo($fen->get_en_passant());
echo($fen->get_halfmove());
echo($fen->get_fullmove());
echo($fen->preview());
```

Initialize custom position and read FEN fields.
``` php
$fen = new Onspli\Chess\FEN('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR b KQq c6 1 2');
echo($fen->export());
echo($fen->export_short());
echo($fen->get_board());
echo($fen->get_active_color());
echo($fen->get_castling());
echo($fen->get_en_passant());
echo($fen->get_halfmove());
echo($fen->get_fullmove());
echo($fen->preview());
```

Manipulate with pieces.
``` php
$fen = new Onspli\Chess\FEN;
echo($fen->get_square('a1'));
$fen->set_square('a1', '');
$fen->set_square('a3', 'R');
echo($fen->preview());
```

Each of the fields can be set with the corresponding setter:
``` php
$fen = new Onspli\Chess\FEN;
echo($fen->export());
$fen->set_board('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR');
$fen->set_active_color('b');
$fen->set_castling('KQq');
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
echo($fen->is_check() ? 'true' : 'false');
echo($fen->is_stalemate() ? 'true' : 'false');
echo($fen->preview());
$fen->move('Ka2');
$fen->move('Qa8');
echo($fen->is_check() ? 'true' : 'false');
echo($fen->is_mate() ? 'true' : 'false');
echo($fen->preview());
```

List all possible moves:
``` php
$fen = new Onspli\Chess\FEN;
$fen->move('e4');
$fen->move('g6');
echo($fen->preview());
print_r($fen->get_legal_moves());
```

Load game in PGN notation and read tags and moves:
``` php
$pgn = new Onspli\Chess\PGN('[Event "Testing"] 1.Nf3 Nf6 2.c4 g6');
echo($pgn->get_tag('Event'));
echo($pgn->get_halfmove(2));
echo($pgn->get_initial_halfmove_number());
echo($pgn->get_last_halfmove_number());
```

Record new moves, add tags and export PGN:
``` php
$pgn = new Onspli\Chess\PGN('[Event "Testing"] 1.Nf3 Nf6 2.c4 g6');
$pgn->set_tag('Site', 'Github');
$pgn->move('a4');
$pgn->move('a5');
print_r($pgn->get_tags());
echo($pgn->export_tags());
echo($pgn->export_movetext());
echo($pgn->export());
```

Extract position after certain move:
``` php
$pgn = new Onspli\Chess\PGN('1.Nf3 Nf6 2.c4 g6');
echo($pgn->get_current_fen());
echo($pgn->get_initial_fen());
echo($pgn->get_fen_after_halfmove(0));
echo($pgn->get_fen_after_halfmove(2));
echo($pgn->get_fen_after_halfmove(Onspli\Chess\PGN::get_halfmove_number(1, 'b')));
```

FEN is returned as `string` by default. Passing parameter `$as_object = true` makes it FEN object:
``` php
$pgn = new Onspli\Chess\PGN('1.Nf3 Nf6 2.c4 g6');
echo($pgn->get_current_fen(true)->preview());
echo($pgn->get_fen_after_halfmove(2, true)->preview());
```

PGN with custom initial position:
``` php
$pgn = new Onspli\Chess\PGN('[FEN "rnbqkb1r/pppp1ppp/5n2/4p3/2P1P3/5N2/PP1P1PPP/RNBQKB1R b KQkq - 0 3"] 3... Nc6 4. Qb3');
echo($pgn->get_initial_fen());
echo($pgn->get_initial_halfmove_number());
echo($pgn->get_halfmove(6));
```

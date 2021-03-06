# Documentation

## Table of Contents

| Method | Description |
|--------|-------------|
| [**Board**](#Board) |  |
| [Board::__construct](#Board__construct) | Load piece placement or setup initial position. |
| [Board::__toString](#Board__toString) |  |
| [Board::export](#Boardexport) | Export piece placement string. |
| [Board::preview](#Boardpreview) | Preview of the board in ASCII graphics. |
| [Board::get_square](#Boardget_square) | Get piece on a particular square. |
| [Board::set_square](#Boardset_square) | Set piece on a particular square. |
| [Board::is_square_vacant](#Boardis_square_vacant) |  |
| [Board::get_defended_squares](#Boardget_defended_squares) | Get array of all squares defended (or attacked) by $defender being on $defender_square. |
| [Board::get_reachable_squares](#Boardget_reachable_squares) | Get array of all squares reachable from $origin_square by $moving_piece. |
| [Board::find_squares_with_piece](#Boardfind_squares_with_piece) | Returns array of squares containing piece. |
| [Board::find_square_with_piece](#Boardfind_square_with_piece) | Returns square containing piece. If there are more pieces, throws. |
| [Board::get_color_of_piece](#Boardget_color_of_piece) | Returns the color of the piece. |
| [Board::get_piece_of_color](#Boardget_piece_of_color) | Converts piece to requested color. |
| [Board::get_opposite_color](#Boardget_opposite_color) | Get color opposite to color passed as an argument. |
| [Board::is_square_attacked_by_piece](#Boardis_square_attacked_by_piece) | Tells whether the square is attacked by particular piece |
| [Board::is_square_attacked](#Boardis_square_attacked) | Tells whether the square is attacked by the color specified. |
| [Board::is_check](#Boardis_check) | Tells whether the king of color specified is in check. |
| [**FEN**](#FEN) | FEN is a standard notation for describing a particular board position of a chess game |
| [FEN::__construct](#FEN__construct) | Load FEN (or Shredder-FEN) or setup starting position. |
| [FEN::__clone](#FEN__clone) |  |
| [FEN::export](#FENexport) | Export whole FEN string. |
| [FEN::export_short](#FENexport_short) | Export FEN string without halfmoves count and fullmove number. |
| [FEN::__toString](#FEN__toString) |  |
| [FEN::preview](#FENpreview) | Preview of the board in ASCII graphics. |
| [FEN::get_board](#FENget_board) | Get piece placement. |
| [FEN::set_board](#FENset_board) | Setup piece placement. |
| [FEN::get_square](#FENget_square) | Get piece on a particular square. |
| [FEN::set_square](#FENset_square) | Set piece on a particular square. |
| [FEN::get_active_color](#FENget_active_color) | Active color. |
| [FEN::set_active_color](#FENset_active_color) | Set active color. |
| [FEN::get_castling](#FENget_castling) | Castling availability. |
| [FEN::set_castling](#FENset_castling) | Set castling availability. |
| [FEN::get_en_passant](#FENget_en_passant) | Get En Passant target square. |
| [FEN::set_en_passant](#FENset_en_passant) | Set En Passant target square. |
| [FEN::get_halfmove](#FENget_halfmove) | Get Halfmove clock |
| [FEN::set_halfmove](#FENset_halfmove) | Set Halfmove clock |
| [FEN::get_fullmove](#FENget_fullmove) | Get Fullmove number |
| [FEN::set_fullmove](#FENset_fullmove) | Set Fullmove number |
| [FEN::is_mate](#FENis_mate) | Returns true if king of active color is in mate. |
| [FEN::is_stalemate](#FENis_stalemate) | Returns true if king of active color is in stalemate. |
| [FEN::is_fifty_move](#FENis_fifty_move) | Returns true if fifty move rule draw can be claimed by active color. |
| [FEN::is_check](#FENis_check) | Returns true if king of active color is in check. |
| [FEN::get_legal_moves](#FENget_legal_moves) | Array of all possible moves in current position. |
| [FEN::is_legal_move](#FENis_legal_move) | Tests if move is legal. |
| [FEN::move](#FENmove) | Perform a move. |
| [**Move**](#Move) | Class for parsing moves in SAN (standard algebraic notation). |
| [Move::__construct](#Move__construct) |  |
| [Move::export](#Moveexport) |  |
| [Move::__toString](#Move__toString) |  |
| [Move::get_capture](#Moveget_capture) |  |
| [Move::get_target](#Moveget_target) |  |
| [Move::get_origin](#Moveget_origin) |  |
| [Move::set_origin](#Moveset_origin) |  |
| [Move::get_piece_type](#Moveget_piece_type) |  |
| [Move::get_castling](#Moveget_castling) |  |
| [Move::get_promotion](#Moveget_promotion) |  |
| [Move::get_check_mate](#Moveget_check_mate) |  |
| [Move::get_annotation](#Moveget_annotation) |  |
| [**NotImplementedException**](#NotImplementedException) |  |
| [**ParseException**](#ParseException) |  |
| [**PGN**](#PGN) | Portable Game Notation (PGN) is a standard plain text format for recording chess games. |
| [PGN::__construct](#PGN__construct) | Initialize object from PGN string. |
| [PGN::export](#PGNexport) | Export PGN string. |
| [PGN::export_movetext](#PGNexport_movetext) | Export movetext section of pgn. |
| [PGN::export_tags](#PGNexport_tags) | Export tag pairs section (headers) of PGN. |
| [PGN::validate_moves](#PGNvalidate_moves) | Validate all moves make sense according to chess rules. |
| [PGN::set_tag](#PGNset_tag) | Set tag pair (header). |
| [PGN::set_initial_fen](#PGNset_initial_fen) | Set custom initial position. |
| [PGN::unset_initial_fen](#PGNunset_initial_fen) | Unset custom initial position - use the standard initial position. |
| [PGN::unset_tag](#PGNunset_tag) | Remove tag pair (header). |
| [PGN::get_tag](#PGNget_tag) | Read tag pair (header) value. |
| [PGN::get_tags](#PGNget_tags) | Get all tags as associative array |
| [PGN::get_initial_fen](#PGNget_initial_fen) | Get initial position. |
| [PGN::get_current_fen](#PGNget_current_fen) | Get FEN of current position. |
| [PGN::get_fen_after_halfmove](#PGNget_fen_after_halfmove) | Get position after given halfmove. |
| [PGN::get_last_halfmove_number](#PGNget_last_halfmove_number) | Get halfmove number of the last recorded move. |
| [PGN::get_initial_halfmove_number](#PGNget_initial_halfmove_number) | Get halfmove number of the first recorder move. |
| [PGN::get_halfmove](#PGNget_halfmove) | Get move in standard algebraic notation. |
| [PGN::get_halfmove_number](#PGNget_halfmove_number) | Converts (move number, color) to halfmove number. |
| [PGN::move](#PGNmove) | Perform move. |
| [**RulesException**](#RulesException) |  |
| [**Square**](#Square) | Class representing coordinates of a square on a chess board. |
| [Square::__construct](#Square__construct) | Create square. |
| [Square::__toString](#Square__toString) |  |
| [Square::get_rank_index](#Squareget_rank_index) | Get rank index of the square. |
| [Square::get_rank](#Squareget_rank) | Get rank of the square. |
| [Square::get_file_index](#Squareget_file_index) | Get file index of the square. |
| [Square::get_file](#Squareget_file) | Get file of the square. |
| [Square::export](#Squareexport) | Returns SAN (standard algebraic notation) string. |
| [Square::is_regular](#Squareis_regular) | Check wether square is regular square. |
| [Square::is_null](#Squareis_null) | Check wether square is null. |
| [Square::is_rank](#Squareis_rank) | Check wether square is rank. |
| [Square::is_file](#Squareis_file) | Check wether square is file. |
| [Square::has_rank](#Squarehas_rank) | Check wether square has rank. |
| [Square::has_file](#Squarehas_file) | Check wether square has file. |
| [Square::get_relative_square](#Squareget_relative_square) | Get square with relative position to this square. |
| [Square::push_to_array](#Squarepush_to_array) | Add square to the end of array. |

## Board





* Full name: \Onspli\Chess\Board


### Board::__construct

Load piece placement or setup initial position.

```php
Board::__construct( string pieces = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR' ): mixed
```

Piece placement (from White's perspective). Each rank is described,
starting with rank 8 and ending with rank 1; within each rank,
the contents of each square are described from file "a" through file "h".
Following the Standard Algebraic Notation (SAN), each piece is identified
by a single letter taken from the standard English names (pawn = "P",
knight = "N", bishop = "B", rook = "R", queen = "Q" and king = "K").
White pieces are designated using upper-case letters ("PNBRQK") while
black pieces use lowercase ("pnbrqk"). Empty squares are noted using
digits 1 through 8 (the number of empty squares), and "/" separates ranks.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `pieces` | **string** |  |


**Return Value:**





---
### Board::__toString



```php
Board::__toString(  ): string
```





**Return Value:**





---
### Board::export

Export piece placement string.

```php
Board::export(  ): string
```

Piece placement (from White's perspective). Each rank is described,
starting with rank 8 and ending with rank 1; within each rank,
the contents of each square are described from file "a" through file "h".
Following the Standard Algebraic Notation (SAN), each piece is identified
by a single letter taken from the standard English names (pawn = "P",
knight = "N", bishop = "B", rook = "R", queen = "Q" and king = "K").
White pieces are designated using upper-case letters ("PNBRQK") while
black pieces use lowercase ("pnbrqk"). Empty squares are noted using
digits 1 through 8 (the number of empty squares), and "/" separates ranks.



**Return Value:**





---
### Board::preview

Preview of the board in ASCII graphics.

```php
Board::preview(  ): string
```





**Return Value:**





---
### Board::get_square

Get piece on a particular square.

```php
Board::get_square( mixed square ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `square` | **mixed** |  |


**Return Value:**

piece - one of PNBRQKpnbrqk or empty string for empty square



---
### Board::set_square

Set piece on a particular square.

```php
Board::set_square( mixed square, string piece ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `square` | **mixed** |  |
| `piece` | **string** |  |


**Return Value:**





---
### Board::is_square_vacant



```php
Board::is_square_vacant( mixed square ): bool
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `square` | **mixed** |  |


**Return Value:**





---
### Board::get_defended_squares

Get array of all squares defended (or attacked) by $defender being on $defender_square.

```php
Board::get_defended_squares( mixed defender_square, string defender, bool as_object = false ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `defender_square` | **mixed** |  |
| `defender` | **string** |  |
| `as_object` | **bool** |  |


**Return Value:**





---
### Board::get_reachable_squares

Get array of all squares reachable from $origin_square by $moving_piece.

```php
Board::get_reachable_squares( mixed origin_square, mixed moving_piece, mixed en_passant_square = '-', bool as_object = false ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `origin_square` | **mixed** |  |
| `moving_piece` | **mixed** |  |
| `en_passant_square` | **mixed** |  |
| `as_object` | **bool** |  |


**Return Value:**





---
### Board::find_squares_with_piece

Returns array of squares containing piece.

```php
Board::find_squares_with_piece( string piece, bool as_object = false ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `piece` | **string** |  |
| `as_object` | **bool** |  |


**Return Value:**





---
### Board::find_square_with_piece

Returns square containing piece. If there are more pieces, throws.

```php
Board::find_square_with_piece( string piece, bool as_object = false ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `piece` | **string** |  |
| `as_object` | **bool** |  |


**Return Value:**





---
### Board::get_color_of_piece

Returns the color of the piece.

```php
Board::get_color_of_piece( string piece ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `piece` | **string** |  |


**Return Value:**

w|b



---
### Board::get_piece_of_color

Converts piece to requested color.

```php
Board::get_piece_of_color( string piece, string color ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `piece` | **string** |  |
| `color` | **string** |  |


**Return Value:**





---
### Board::get_opposite_color

Get color opposite to color passed as an argument.

```php
Board::get_opposite_color( string color ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `color` | **string** |  |


**Return Value:**





---
### Board::is_square_attacked_by_piece

Tells whether the square is attacked by particular piece

```php
Board::is_square_attacked_by_piece( mixed square, string piece ): bool
```

The method also distinguishes the color of the piece.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `square` | **mixed** |  |
| `piece` | **string** |  |


**Return Value:**





---
### Board::is_square_attacked

Tells whether the square is attacked by the color specified.

```php
Board::is_square_attacked( mixed square, string attacking_color ): bool
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `square` | **mixed** |  |
| `attacking_color` | **string** |  |


**Return Value:**





---
### Board::is_check

Tells whether the king of color specified is in check.

```php
Board::is_check( string color ): bool
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `color` | **string** |  |


**Return Value:**





---
## FEN

FEN is a standard notation for describing a particular board position of a chess game

Forsyth???Edwards Notation (FEN) is a standard notation for describing a particular board
position of a chess game. The purpose of FEN is to provide all the necessary information
to restart a game from a particular position.

Class provides intefrace for reading and setting all FEN fields, and also
method for checking game state (check, mate, stalemate, fifty-move rule draw),
getting all available moves in the position and changing the position by performing
move according to chess rules.

* Full name: \Onspli\Chess\FEN


### FEN::__construct

Load FEN (or Shredder-FEN) or setup starting position.

```php
FEN::__construct( string fen = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1' ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `fen` | **string** |  |


**Return Value:**





---
### FEN::__clone



```php
FEN::__clone(  ): mixed
```





**Return Value:**





---
### FEN::export

Export whole FEN string.

```php
FEN::export(  ): string
```





**Return Value:**

FEN string



---
### FEN::export_short

Export FEN string without halfmoves count and fullmove number.

```php
FEN::export_short(  ): string
```

They really are not so important to describe chess position.



**Return Value:**





---
### FEN::__toString



```php
FEN::__toString(  ): string
```





**Return Value:**





---
### FEN::preview

Preview of the board in ASCII graphics.

```php
FEN::preview(  ): string
```





**Return Value:**

ascii preview of the board



---
### FEN::get_board

Get piece placement.

```php
FEN::get_board( bool as_object = false ): string|\Onspli\Chess\Board
```

Piece placement (from White's perspective). Each rank is described,
starting with rank 8 and ending with rank 1; within each rank,
the contents of each square are described from file "a" through file "h".
Following the Standard Algebraic Notation (SAN), each piece is identified
by a single letter taken from the standard English names (pawn = "P",
knight = "N", bishop = "B", rook = "R", queen = "Q" and king = "K").
White pieces are designated using upper-case letters ("PNBRQK") while
black pieces use lowercase ("pnbrqk"). Empty squares are noted using
digits 1 through 8 (the number of empty squares), and "/" separates ranks.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `as_object` | **bool** | return object instead of string |


**Return Value:**





---
### FEN::set_board

Setup piece placement.

```php
FEN::set_board( string|\Onspli\Chess\Board board ): void
```

Piece placement (from White's perspective). Each rank is described,
starting with rank 8 and ending with rank 1; within each rank,
the contents of each square are described from file "a" through file "h".
Following the Standard Algebraic Notation (SAN), each piece is identified
by a single letter taken from the standard English names (pawn = "P",
knight = "N", bishop = "B", rook = "R", queen = "Q" and king = "K").
White pieces are designated using upper-case letters ("PNBRQK") while
black pieces use lowercase ("pnbrqk"). Empty squares are noted using
digits 1 through 8 (the number of empty squares), and "/" separates ranks.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `board` | **string\|\Onspli\Chess\Board** | piece placement |


**Return Value:**





---
### FEN::get_square

Get piece on a particular square.

```php
FEN::get_square( mixed square ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `square` | **mixed** |  |


**Return Value:**

piece - one of PNBRQKpnbrqk or empty string for empty square



---
### FEN::set_square

Set piece on a particular square.

```php
FEN::set_square( mixed square, string piece ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `square` | **mixed** |  |
| `piece` | **string** |  |


**Return Value:**





---
### FEN::get_active_color

Active color.

```php
FEN::get_active_color(  ): string
```

"w" means White moves next, "b" means Black moves next.



**Return Value:**

w|b



---
### FEN::set_active_color

Set active color.

```php
FEN::set_active_color( string color ): void
```

"w" means White moves next, "b" means Black moves next.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `color` | **string** | w\|b |


**Return Value:**





---
### FEN::get_castling

Castling availability.

```php
FEN::get_castling(  ): string
```

If neither side can castle, this is "-".
Otherwise, this has one or more letters: "K" (White can castle kingside),
"Q" (White can castle queenside), "k" (Black can castle kingside), and/or
"q" (Black can castle queenside). A move that temporarily prevents castling
does not negate this notation.
Shredder-FEN is also supported - instead of K and Q the letters
of files containing rooks are used. For standard chess its AHah.
X-FEN is not supported.



**Return Value:**

castling availability string



---
### FEN::set_castling

Set castling availability.

```php
FEN::set_castling( string castling ): void
```

If neither side can castle, this is "-".
Otherwise, this has one or more letters: "K" (White can castle kingside),
"Q" (White can castle queenside), "k" (Black can castle kingside), and/or
"q" (Black can castle queenside). A move that temporarily prevents castling
does not negate this notation.
Shredder-FEN is also supported - instead of K and Q the letters
of files containing rooks are used. For standard chess its AHah.
X-FEN is not supported.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `castling` | **string** |  |


**Return Value:**





---
### FEN::get_en_passant

Get En Passant target square.

```php
FEN::get_en_passant( bool as_object = false ): string|\Onspli\Chess\Square
```

En passant target square in algebraic notation. If there's no en passant
target square, this is "-". If a pawn has just made a two-square move,
this is the position "behind" the pawn. This is recorded regardless of
whether there is a pawn in position to make an en passant capture.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `as_object` | **bool** | return object instead of string |


**Return Value:**





---
### FEN::set_en_passant

Set En Passant target square.

```php
FEN::set_en_passant( mixed square ): void
```

En passant target square in algebraic notation. If there's no en passant
target square, this is "-". If a pawn has just made a two-square move,
this is the position "behind" the pawn. This is recorded regardless of
whether there is a pawn in position to make an en passant capture.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `square` | **mixed** |  |


**Return Value:**





---
### FEN::get_halfmove

Get Halfmove clock

```php
FEN::get_halfmove(  ): int
```

The number of halfmoves since the last capture or pawn
advance, used for the fifty-move rule.



**Return Value:**





---
### FEN::set_halfmove

Set Halfmove clock

```php
FEN::set_halfmove( mixed halfmove ): void
```

The number of halfmoves since the last capture or pawn
advance, used for the fifty-move rule.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `halfmove` | **mixed** |  |


**Return Value:**





---
### FEN::get_fullmove

Get Fullmove number

```php
FEN::get_fullmove(  ): int
```

The number of the full move. It starts at 1, and is
incremented after Black's move.



**Return Value:**





---
### FEN::set_fullmove

Set Fullmove number

```php
FEN::set_fullmove( mixed fullmove ): void
```

The number of the full move. It starts at 1, and is
incremented after Black's move.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `fullmove` | **mixed** |  |


**Return Value:**





---
### FEN::is_mate

Returns true if king of active color is in mate.

```php
FEN::is_mate(  ): bool
```





**Return Value:**





---
### FEN::is_stalemate

Returns true if king of active color is in stalemate.

```php
FEN::is_stalemate(  ): bool
```





**Return Value:**





---
### FEN::is_fifty_move

Returns true if fifty move rule draw can be claimed by active color.

```php
FEN::is_fifty_move(  ): bool
```





**Return Value:**





---
### FEN::is_check

Returns true if king of active color is in check.

```php
FEN::is_check(  ): bool
```





**Return Value:**





---
### FEN::get_legal_moves

Array of all possible moves in current position.

```php
FEN::get_legal_moves(  ): array
```





**Return Value:**





---
### FEN::is_legal_move

Tests if move is legal.

```php
FEN::is_legal_move( string move ): bool
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `move` | **string** |  |


**Return Value:**





---
### FEN::move

Perform a move.

```php
FEN::move( string move ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `move` | **string** |  |


**Return Value:**





---
## Move

Class for parsing moves in SAN (standard algebraic notation).



* Full name: \Onspli\Chess\Move


### Move::__construct



```php
Move::__construct( string move ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `move` | **string** |  |


**Return Value:**





---
### Move::export



```php
Move::export(  ): string
```





**Return Value:**





---
### Move::__toString



```php
Move::__toString(  ): string
```





**Return Value:**





---
### Move::get_capture



```php
Move::get_capture(  ): bool
```





**Return Value:**





---
### Move::get_target



```php
Move::get_target( bool as_object = false ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `as_object` | **bool** |  |


**Return Value:**





---
### Move::get_origin



```php
Move::get_origin( bool as_object = false ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `as_object` | **bool** |  |


**Return Value:**





---
### Move::set_origin



```php
Move::set_origin( mixed square ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `square` | **mixed** |  |


**Return Value:**





---
### Move::get_piece_type



```php
Move::get_piece_type(  ): string
```





**Return Value:**





---
### Move::get_castling



```php
Move::get_castling(  ): string
```





**Return Value:**





---
### Move::get_promotion



```php
Move::get_promotion(  ): string
```





**Return Value:**





---
### Move::get_check_mate



```php
Move::get_check_mate(  ): string
```





**Return Value:**





---
### Move::get_annotation



```php
Move::get_annotation(  ): string
```





**Return Value:**





---
## NotImplementedException





* Full name: \Onspli\Chess\NotImplementedException
* Parent class: 


## ParseException





* Full name: \Onspli\Chess\ParseException
* Parent class: 


## PGN

Portable Game Notation (PGN) is a standard plain text format for recording chess games.

Portable Game Notation (PGN) is a standard plain text format for recording
chess games (both the moves and related data), which can be read by humans
and is also supported by most chess software.

* Full name: \Onspli\Chess\PGN


### PGN::__construct

Initialize object from PGN string.

```php
PGN::__construct( string pgn = '' ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `pgn` | **string** |  |


**Return Value:**





---
### PGN::export

Export PGN string.

```php
PGN::export(  ): string
```





**Return Value:**





---
### PGN::export_movetext

Export movetext section of pgn.

```php
PGN::export_movetext(  ): string
```





**Return Value:**





---
### PGN::export_tags

Export tag pairs section (headers) of PGN.

```php
PGN::export_tags(  ): string
```





**Return Value:**





---
### PGN::validate_moves

Validate all moves make sense according to chess rules.

```php
PGN::validate_moves(  ): void
```





**Return Value:**





---
### PGN::set_tag

Set tag pair (header).

```php
PGN::set_tag( string name, ?string value ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `name` | **string** |  |
| `value` | **?string** |  |


**Return Value:**





---
### PGN::set_initial_fen

Set custom initial position.

```php
PGN::set_initial_fen( string fen ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `fen` | **string** |  |


**Return Value:**





---
### PGN::unset_initial_fen

Unset custom initial position - use the standard initial position.

```php
PGN::unset_initial_fen(  ): void
```





**Return Value:**





---
### PGN::unset_tag

Remove tag pair (header).

```php
PGN::unset_tag( string name ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `name` | **string** |  |


**Return Value:**





---
### PGN::get_tag

Read tag pair (header) value.

```php
PGN::get_tag( string name ): ?string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `name` | **string** |  |


**Return Value:**





---
### PGN::get_tags

Get all tags as associative array

```php
PGN::get_tags(  ): array
```





**Return Value:**





---
### PGN::get_initial_fen

Get initial position.

```php
PGN::get_initial_fen( bool as_object = false ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `as_object` | **bool** |  |


**Return Value:**





---
### PGN::get_current_fen

Get FEN of current position.

```php
PGN::get_current_fen( bool as_object = false ): mixed
```

Return position after last move.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `as_object` | **bool** |  |


**Return Value:**





---
### PGN::get_fen_after_halfmove

Get position after given halfmove.

```php
PGN::get_fen_after_halfmove( int halfmove_number, bool as_object = false ): mixed
```

Some edge cases:
```php
$pgn->get_initial_fen() = $pgn->get_fen_after_halfmove($pgn->get_initial_halfmove_number() - 1);
$pgn->get_current_fen() = $pgn->get_fen_after_halfmove($pgn->get_last_halfmove_number());
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `halfmove_number` | **int** |  |
| `as_object` | **bool** |  |


**Return Value:**





---
### PGN::get_last_halfmove_number

Get halfmove number of the last recorded move.

```php
PGN::get_last_halfmove_number(  ): int
```





**Return Value:**





---
### PGN::get_initial_halfmove_number

Get halfmove number of the first recorder move.

```php
PGN::get_initial_halfmove_number(  ): int
```





**Return Value:**





---
### PGN::get_halfmove

Get move in standard algebraic notation.

```php
PGN::get_halfmove( int halfmove_number, bool as_object = false ): mixed
```

Halfmove number starts with 1 (white's first move). One move has
two halfmoves (for white and black player).


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `halfmove_number` | **int** |  |
| `as_object` | **bool** |  |


**Return Value:**





---
### PGN::get_halfmove_number

Converts (move number, color) to halfmove number.

```php
PGN::get_halfmove_number( int move_number, string color ): int
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `move_number` | **int** |  |
| `color` | **string** |  |


**Return Value:**





---
### PGN::move

Perform move.

```php
PGN::move( string move ): void
```

The method validates syntax of the move, however it doesn't check
the move is valid acording to chess rules.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `move` | **string** |  |


**Return Value:**





---
## RulesException





* Full name: \Onspli\Chess\RulesException
* Parent class: 


## Square

Class representing coordinates of a square on a chess board.

Square can be on of the following types:
- regular square - 'e4'
- file - 'e' for e-file
- rank - '4' for 4th rank
- null square - '-', '', square not on a board

* Full name: \Onspli\Chess\Square


### Square::__construct

Create square.

```php
Square::__construct( mixed san_or_file_index = null, mixed rank_index = null ): mixed
```

Constructor accepts either SAN (standard algebraic notation) string,
or file and rank indexes.
 - regular square: `new Square('e4'); new Square(4, 3);`
 - null square: `new Square; new Square('-'); new Square(null, null);`
 - file: `new Square('e'); new Square(4, null);`
 - rank: `new Square('4'); new Square(null, 3);`

Throws `ParseException` if SAN is invalid or if indices are not integers.
Creates null square if file or rank index is out of bounds.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `san_or_file_index` | **mixed** |  |
| `rank_index` | **mixed** |  |


**Return Value:**





---
### Square::__toString



```php
Square::__toString(  ): string
```





**Return Value:**





---
### Square::get_rank_index

Get rank index of the square.

```php
Square::get_rank_index(  ): int
```

For square 'e4' it returns 3.



**Return Value:**





---
### Square::get_rank

Get rank of the square.

```php
Square::get_rank(  ): string
```

For square 'e4' it returns '4'. Throws `\OutOfBoundsException` for null squares.
Returns empty string for files.



**Return Value:**





---
### Square::get_file_index

Get file index of the square.

```php
Square::get_file_index(  ): int
```

For square 'e4' it returns 4.



**Return Value:**





---
### Square::get_file

Get file of the square.

```php
Square::get_file(  ): string
```

For square 'e4' it returns 'e'. Throws `\OutOfBoundsException` for null squares.
Returns empty string for ranks.



**Return Value:**





---
### Square::export

Returns SAN (standard algebraic notation) string.

```php
Square::export(  ): string
```

For
- regular square - 'e4'
- file - 'e'
- rank - '4',
- null square - '-'



**Return Value:**





---
### Square::is_regular

Check wether square is regular square.

```php
Square::is_regular(  ): bool
```





**Return Value:**





---
### Square::is_null

Check wether square is null.

```php
Square::is_null(  ): bool
```





**Return Value:**





---
### Square::is_rank

Check wether square is rank.

```php
Square::is_rank(  ): bool
```





**Return Value:**





---
### Square::is_file

Check wether square is file.

```php
Square::is_file(  ): bool
```





**Return Value:**





---
### Square::has_rank

Check wether square has rank.

```php
Square::has_rank(  ): bool
```

Regular squares and ranks has rank.



**Return Value:**





---
### Square::has_file

Check wether square has file.

```php
Square::has_file(  ): bool
```

Regular squares and files has file.



**Return Value:**





---
### Square::get_relative_square

Get square with relative position to this square.

```php
Square::get_relative_square( int east, int north ): mixed
```

Throws `\OutOfBoundsException` when trying to get relative square of a non
regular squares. Returns null square if relative coordinates are outside
the board.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `east` | **int** |  |
| `north` | **int** |  |


**Return Value:**





---
### Square::push_to_array

Add square to the end of array.

```php
Square::push_to_array( array &array, bool as_object = false ): void
```

Method ignores nonregular squares.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `array` | **array** |  |
| `as_object` | **bool** | Add square as an object rather than SAN string. |


**Return Value:**





---

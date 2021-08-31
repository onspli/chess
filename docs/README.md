# Documentation

## Table of Contents

| Method | Description |
|--------|-------------|
| [**Board**](#Board) |  |
| [Board::__construct](#Board__construct) | Piece placement (from White&#039;s perspective). Each rank is described,starting with rank 8 and ending with rank 1; within each rank,the contents of each square are described from file &quot;a&quot; through file &quot;h&quot;. |
| [Board::export](#Boardexport) |  |
| [Board::preview](#Boardpreview) | Preview of the board in ASCII graphics. |
| [Board::get_square](#Boardget_square) |  |
| [Board::set_square](#Boardset_square) |  |
| [Board::get_defended_squares](#Boardget_defended_squares) | Get array of all squares defended (or attacked) by $defender being on $defender_square. |
| [Board::get_reachable_squares](#Boardget_reachable_squares) | Get array of all squares reachable from $origin_square by $moving_piece. |
| [Board::get_pieces_on_squares](#Boardget_pieces_on_squares) | Get list of pieces on squares (including multiplicities, excluding blank squares). |
| [Board::find_squares_with_piece](#Boardfind_squares_with_piece) | Returns array of squares containing piece. |
| [Board::copy](#Boardcopy) |  |
| [Board::get_active_piece](#Boardget_active_piece) |  |
| [Board::get_opponents_piece](#Boardget_opponents_piece) |  |
| [Board::get_piece_color](#Boardget_piece_color) |  |
| [Board::is_check](#Boardis_check) | Returns true if king of active color is in check. |
| [**FEN**](#FEN) | FEN is a standard notation for describing a particular board position of a chess game |
| [FEN::__construct](#FEN__construct) | Load FEN or setup starting position. |
| [FEN::export](#FENexport) | Export whole FEN string. |
| [FEN::preview](#FENpreview) | Preview of the board in ASCII graphics. |
| [FEN::get_board](#FENget_board) | Get piece placement. |
| [FEN::set_board](#FENset_board) | Setup piece placement. |
| [FEN::get_square](#FENget_square) | Get piece on a particular square. |
| [FEN::set_square](#FENset_square) | Set piece on a particular square. |
| [FEN::get_active_color](#FENget_active_color) | Active color. |
| [FEN::set_active_color](#FENset_active_color) | Set active color. |
| [FEN::get_castling_string](#FENget_castling_string) | Castling availability. |
| [FEN::set_castling_string](#FENset_castling_string) | Set castling availability. |
| [FEN::get_castling_availability](#FENget_castling_availability) |  |
| [FEN::set_castling_availability](#FENset_castling_availability) |  |
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
| [FEN::get_possible_moves](#FENget_possible_moves) | Array of all possible moves in current position. |
| [FEN::move](#FENmove) | Perform a move. |
| [**Move**](#Move) | Class for parsing moves in SAN (standard algebraic notation). |
| [Move::__construct](#Move__construct) |  |
| [Move::export](#Moveexport) |  |
| [Move::get_capture](#Moveget_capture) |  |
| [Move::get_target](#Moveget_target) |  |
| [Move::get_origin](#Moveget_origin) |  |
| [Move::get_piece](#Moveget_piece) |  |
| [Move::get_castling](#Moveget_castling) |  |
| [Move::get_promotion](#Moveget_promotion) |  |
| [Move::get_check_mate](#Moveget_check_mate) |  |
| [Move::get_annotation](#Moveget_annotation) |  |
| [**NotImplementedException**](#NotImplementedException) |  |
| [**ParseException**](#ParseException) |  |
| [**RulesException**](#RulesException) |  |
| [**Square**](#Square) | Class representing coordinates of a square on a chess board. |
| [Square::__construct](#Square__construct) | Create square. |
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

Piece placement (from White's perspective). Each rank is described,
starting with rank 8 and ending with rank 1; within each rank,
the contents of each square are described from file "a" through file "h".

```php
Board::__construct( string pieces = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR' ): mixed
```

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
### Board::export



```php
Board::export(  ): string
```





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



```php
Board::get_square( mixed square ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `square` | **mixed** |  |


**Return Value:**





---
### Board::set_square



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
### Board::get_pieces_on_squares

Get list of pieces on squares (including multiplicities, excluding blank squares).

```php
Board::get_pieces_on_squares( array squares ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `squares` | **array** |  |


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
### Board::copy



```php
Board::copy(  ): mixed
```





**Return Value:**





---
### Board::get_active_piece



```php
Board::get_active_piece( string piece, string active_color ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `piece` | **string** |  |
| `active_color` | **string** |  |


**Return Value:**





---
### Board::get_opponents_piece



```php
Board::get_opponents_piece( string piece, string active_color ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `piece` | **string** |  |
| `active_color` | **string** |  |


**Return Value:**





---
### Board::get_piece_color



```php
Board::get_piece_color( string piece ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `piece` | **string** |  |


**Return Value:**





---
### Board::is_check

Returns true if king of active color is in check.

```php
Board::is_check( string active ): bool
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `active` | **string** |  |


**Return Value:**





---
## FEN

FEN is a standard notation for describing a particular board position of a chess game

Forsyth–Edwards Notation (FEN) is a standard notation for describing a particular board
position of a chess game. The purpose of FEN is to provide all the necessary information
to restart a game from a particular position.

Class provides intefrace for reading and setting all FEN fields, and also
method for checking game state (check, mate, stalemate, fifty-move rule draw),
getting all available moves in the position and changing the position by performing
move according to chess rules.

* Full name: \Onspli\Chess\FEN


### FEN::__construct

Load FEN or setup starting position.

```php
FEN::__construct( string fen = '' ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `fen` | **string** |  |


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
### FEN::get_castling_string

Castling availability.

```php
FEN::get_castling_string(  ): string
```

If neither side can castle, this is "-".
Otherwise, this has one or more letters: "K" (White can castle kingside),
"Q" (White can castle queenside), "k" (Black can castle kingside), and/or
"q" (Black can castle queenside). A move that temporarily prevents castling
does not negate this notation.



**Return Value:**

castling availability string



---
### FEN::set_castling_string

Set castling availability.

```php
FEN::set_castling_string( string castling ): void
```

If neither side can castle, this is "-".
Otherwise, this has one or more letters: "K" (White can castle kingside),
"Q" (White can castle queenside), "k" (Black can castle kingside), and/or
"q" (Black can castle queenside). A move that temporarily prevents castling
does not negate this notation.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `castling` | **string** |  |


**Return Value:**





---
### FEN::get_castling_availability



```php
FEN::get_castling_availability( string type ): bool
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `type` | **string** |  |


**Return Value:**





---
### FEN::set_castling_availability



```php
FEN::set_castling_availability( string type, bool avalability ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `type` | **string** |  |
| `avalability` | **bool** |  |


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
### FEN::get_possible_moves

Array of all possible moves in current position.

```php
FEN::get_possible_moves(  ): array
```





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
### Move::get_piece



```php
Move::get_piece(  ): string
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
### Square::get_rank_index

Get rank index of the square.

```php
Square::get_rank_index(  ): int
```

For square 'e4' it returns 3. Throws `\OutOfBoundsException` for null squares
and files.



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

For square 'e4' it returns 4. Throws `\OutOfBoundsException` for null squares
and ranks.



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

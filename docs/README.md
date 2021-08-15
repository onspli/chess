# Documentation

## Table of Contents

* [Board](#Board) 
    * [Board::__construct](#Board__construct) Piece placement (from White's perspective). Each rank is described,
starting with rank 8 and ending with rank 1; within each rank,
the contents of each square are described from file "a" through file "h".
    * [Board::export](#Boardexport) 
    * [Board::square](#Boardsquare) 
    * [Board::square_nothrow](#Boardsquare_nothrow) 
    * [Board::set_square](#Boardset_square) 
    * [Board::set_square_nothrow](#Boardset_square_nothrow) 
    * [Board::attacked_squares](#Boardattacked_squares) Get array of all squares attacked (or defended) by $attacking_piece being on $attacker_square.
    * [Board::pieces_on_squares](#Boardpieces_on_squares) Get list of pieces on squares (including multiplicities, excluding blank squares).
    * [Board::find](#Boardfind) Returns array of squares containing piece.
    * [Board::copy](#Boardcopy) 
    * [Board::active_piece](#Boardactive_piece) 
    * [Board::opponents_piece](#Boardopponents_piece) 
    * [Board::is_check](#Boardis_check) Returns true if king of active color is in check.
    * [Board::preview](#Boardpreview) Preview of the board in ASCII graphics.
* [FEN](#FEN) 
    * [FEN::__construct](#FEN__construct) Load fen or setup starting position.
    * [FEN::export](#FENexport) Export whole FEN string
    * [FEN::preview](#FENpreview) Preview of the board in ASCII graphics.
    * [FEN::board](#FENboard) Piece placement (from White's perspective). Each rank is described,
starting with rank 8 and ending with rank 1; within each rank,
the contents of each square are described from file "a" through file "h".
    * [FEN::set_board](#FENset_board) 
    * [FEN::square](#FENsquare) 
    * [FEN::set_square](#FENset_square) 
    * [FEN::active](#FENactive) Active color. "w" means White moves next, "b" means Black moves next.
    * [FEN::set_active](#FENset_active) 
    * [FEN::castling](#FENcastling) Castling availability. If neither side can castle, this is "-".
    * [FEN::set_castling](#FENset_castling) 
    * [FEN::castling_availability](#FENcastling_availability) 
    * [FEN::set_castling_availability](#FENset_castling_availability) 
    * [FEN::en_passant](#FENen_passant) En passant target square in algebraic notation. If there's no en passant
target square, this is "-". If a pawn has just made a two-square move,
this is the position "behind" the pawn. This is recorded regardless of
whether there is a pawn in position to make an en passant capture.
    * [FEN::set_en_passant](#FENset_en_passant) 
    * [FEN::halfmove](#FENhalfmove) Halfmove clock: The number of halfmoves since the last capture or pawn
advance, used for the fifty-move rule.
    * [FEN::set_halfmove](#FENset_halfmove) 
    * [FEN::fullmove](#FENfullmove) Fullmove number: The number of the full move. It starts at 1, and is
incremented after Black's move.
    * [FEN::set_fullmove](#FENset_fullmove) 
    * [FEN::is_mate](#FENis_mate) Returns true if king of active color is in mate.
    * [FEN::is_stalemate](#FENis_stalemate) Returns true if king of active color is in stalemate.
    * [FEN::is_fifty_move](#FENis_fifty_move) Returns true if fifty move rule draw can be claimed by active color.
    * [FEN::is_check](#FENis_check) Returns true if king of active color is in check.
    * [FEN::possible_moves](#FENpossible_moves) Array of all possible moves in current position.
    * [FEN::move](#FENmove) Perform a move.
* [Move](#Move) 
    * [Move::__construct](#Move__construct) 
    * [Move::alg](#Movealg) 
    * [Move::capture](#Movecapture) 
    * [Move::target](#Movetarget) 
    * [Move::origin_file](#Moveorigin_file) 
    * [Move::origin_rank](#Moveorigin_rank) 
    * [Move::piece](#Movepiece) 
    * [Move::castling](#Movecastling) 
    * [Move::promotion](#Movepromotion) 
    * [Move::check_mate](#Movecheck_mate) 
    * [Move::annotation](#Moveannotation) 
* [NotImplementedException](#NotImplementedException) 
* [ParseException](#ParseException) 
* [RulesException](#RulesException) 
* [Square](#Square) There are two handy notations of squares on the chess board.
    * [Square::__construct](#Square__construct) 
    * [Square::rank](#Squarerank) 
    * [Square::file](#Squarefile) 
    * [Square::alg](#Squarealg) 
    * [Square::is_null](#Squareis_null) 
    * [Square::n](#Squaren) 
    * [Square::w](#Squarew) 
    * [Square::s](#Squares) 
    * [Square::e](#Squaree) 
    * [Square::nw](#Squarenw) 
    * [Square::ne](#Squarene) 
    * [Square::sw](#Squaresw) 
    * [Square::se](#Squarese) 
    * [Square::rel](#Squarerel) 
    * [Square::add_to](#Squareadd_to) 

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
### Board::square



```php
Board::square( mixed square ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `square` | **mixed** |  |


**Return Value:**





---
### Board::square_nothrow



```php
Board::square_nothrow( mixed square ): string
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
### Board::set_square_nothrow



```php
Board::set_square_nothrow( mixed square, string piece ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `square` | **mixed** |  |
| `piece` | **string** |  |


**Return Value:**





---
### Board::attacked_squares

Get array of all squares attacked (or defended) by $attacking_piece being on $attacker_square.

```php
Board::attacked_squares( mixed attacker_square, mixed attacking_piece, bool as_object = false ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `attacker_square` | **mixed** |  |
| `attacking_piece` | **mixed** |  |
| `as_object` | **bool** |  |


**Return Value:**





---
### Board::pieces_on_squares

Get list of pieces on squares (including multiplicities, excluding blank squares).

```php
Board::pieces_on_squares( array squares ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `squares` | **array** |  |


**Return Value:**





---
### Board::find

Returns array of squares containing piece.

```php
Board::find( string piece, bool as_object = false ): array
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
### Board::active_piece



```php
Board::active_piece( string piece, string active ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `piece` | **string** |  |
| `active` | **string** |  |


**Return Value:**





---
### Board::opponents_piece



```php
Board::opponents_piece( string piece, string active ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `piece` | **string** |  |
| `active` | **string** |  |


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
### Board::preview

Preview of the board in ASCII graphics.

```php
Board::preview(  ): string
```





**Return Value:**





---
## FEN





* Full name: \Onspli\Chess\FEN


### FEN::__construct

Load fen or setup starting position.

```php
FEN::__construct( string fen = '' ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `fen` | **string** |  |


**Return Value:**





---
### FEN::export

Export whole FEN string

```php
FEN::export(  ): string
```





**Return Value:**





---
### FEN::preview

Preview of the board in ASCII graphics.

```php
FEN::preview(  ): string
```





**Return Value:**





---
### FEN::board

Piece placement (from White's perspective). Each rank is described,
starting with rank 8 and ending with rank 1; within each rank,
the contents of each square are described from file "a" through file "h".

```php
FEN::board( bool as_object = false ): mixed
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
| `as_object` | **bool** |  |


**Return Value:**





---
### FEN::set_board



```php
FEN::set_board( mixed board ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `board` | **mixed** |  |


**Return Value:**





---
### FEN::square



```php
FEN::square( mixed square ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `square` | **mixed** |  |


**Return Value:**





---
### FEN::set_square



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
### FEN::active

Active color. "w" means White moves next, "b" means Black moves next.

```php
FEN::active(  ): string
```





**Return Value:**





---
### FEN::set_active



```php
FEN::set_active( string color ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `color` | **string** |  |


**Return Value:**





---
### FEN::castling

Castling availability. If neither side can castle, this is "-".

```php
FEN::castling(  ): string
```

Otherwise, this has one or more letters: "K" (White can castle kingside),
"Q" (White can castle queenside), "k" (Black can castle kingside), and/or
"q" (Black can castle queenside). A move that temporarily prevents castling
does not negate this notation.



**Return Value:**





---
### FEN::set_castling



```php
FEN::set_castling( string castling ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `castling` | **string** |  |


**Return Value:**





---
### FEN::castling_availability



```php
FEN::castling_availability( string type ): bool
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
### FEN::en_passant

En passant target square in algebraic notation. If there's no en passant
target square, this is "-". If a pawn has just made a two-square move,
this is the position "behind" the pawn. This is recorded regardless of
whether there is a pawn in position to make an en passant capture.

```php
FEN::en_passant( bool as_object = false ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `as_object` | **bool** |  |


**Return Value:**





---
### FEN::set_en_passant



```php
FEN::set_en_passant( mixed square ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `square` | **mixed** |  |


**Return Value:**





---
### FEN::halfmove

Halfmove clock: The number of halfmoves since the last capture or pawn
advance, used for the fifty-move rule.

```php
FEN::halfmove(  ): int
```





**Return Value:**





---
### FEN::set_halfmove



```php
FEN::set_halfmove( mixed halfmove ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `halfmove` | **mixed** |  |


**Return Value:**





---
### FEN::fullmove

Fullmove number: The number of the full move. It starts at 1, and is
incremented after Black's move.

```php
FEN::fullmove(  ): int
```





**Return Value:**





---
### FEN::set_fullmove



```php
FEN::set_fullmove( mixed fullmove ): void
```




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
### FEN::possible_moves

Array of all possible moves in current position.

```php
FEN::possible_moves(  ): array
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
### Move::alg



```php
Move::alg(  ): string
```





**Return Value:**





---
### Move::capture



```php
Move::capture(  ): bool
```





**Return Value:**





---
### Move::target



```php
Move::target( bool as_object = false ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `as_object` | **bool** |  |


**Return Value:**





---
### Move::origin_file



```php
Move::origin_file( bool as_index = false ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `as_index` | **bool** |  |


**Return Value:**





---
### Move::origin_rank



```php
Move::origin_rank( bool as_index = false ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `as_index` | **bool** |  |


**Return Value:**





---
### Move::piece



```php
Move::piece(  ): string
```





**Return Value:**





---
### Move::castling



```php
Move::castling(  ): string
```





**Return Value:**





---
### Move::promotion



```php
Move::promotion(  ): string
```





**Return Value:**





---
### Move::check_mate



```php
Move::check_mate(  ): string
```





**Return Value:**





---
### Move::annotation



```php
Move::annotation(  ): string
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

There are two handy notations of squares on the chess board.

The human-readable algebraic notation (e4), and zero based coordinates (e4 = [4,3])
familiar to programmers.
The class helps conversion between these two notations.
Lets also consider special null square '-' (ie for en passant).
Square can be either constructed as new Square('e4')
or new Square(4, 3).

* Full name: \Onspli\Chess\Square


### Square::__construct



```php
Square::__construct( mixed file_or_alg = null, mixed rank = null ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `file_or_alg` | **mixed** |  |
| `rank` | **mixed** |  |


**Return Value:**





---
### Square::rank



```php
Square::rank(  ): int
```





**Return Value:**





---
### Square::file



```php
Square::file(  ): int
```





**Return Value:**





---
### Square::alg



```php
Square::alg(  ): string
```





**Return Value:**





---
### Square::is_null



```php
Square::is_null(  ): bool
```





**Return Value:**





---
### Square::n



```php
Square::n(  ): mixed
```





**Return Value:**





---
### Square::w



```php
Square::w(  ): mixed
```





**Return Value:**





---
### Square::s



```php
Square::s(  ): mixed
```





**Return Value:**





---
### Square::e



```php
Square::e(  ): mixed
```





**Return Value:**





---
### Square::nw



```php
Square::nw(  ): mixed
```





**Return Value:**





---
### Square::ne



```php
Square::ne(  ): mixed
```





**Return Value:**





---
### Square::sw



```php
Square::sw(  ): mixed
```





**Return Value:**





---
### Square::se



```php
Square::se(  ): mixed
```





**Return Value:**





---
### Square::rel



```php
Square::rel( mixed east, mixed north ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `east` | **mixed** |  |
| `north` | **mixed** |  |


**Return Value:**





---
### Square::add_to



```php
Square::add_to( array &array, bool as_object = false ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `array` | **array** |  |
| `as_object` | **bool** |  |


**Return Value:**





---


--------
> This document was automatically generated from source code comments on 2021-08-15 using [phpDocumentor](http://www.phpdoc.org/) and [cvuorinen/phpdoc-markdown-public](https://github.com/cvuorinen/phpdoc-markdown-public)

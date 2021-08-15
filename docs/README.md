# Documentation

## Table of Contents

* [Board](#Board)
    * [__construct](#__construct)
    * [export](#export)
    * [square](#square)
    * [square_nothrow](#square_nothrow)
    * [set_square](#set_square)
    * [set_square_nothrow](#set_square_nothrow)
    * [attacked_squares](#attacked_squares)
    * [pieces_on_squares](#pieces_on_squares)
    * [find](#find)
    * [copy](#copy)
    * [active_piece](#active_piece)
    * [opponents_piece](#opponents_piece)
    * [is_check](#is_check)
    * [preview](#preview)
* [FEN](#FEN)
    * [__construct](#__construct)
    * [export](#export)
    * [preview](#preview)
    * [board](#board)
    * [set_board](#set_board)
    * [square](#square)
    * [set_square](#set_square)
    * [active](#active)
    * [set_active](#set_active)
    * [castling](#castling)
    * [set_castling](#set_castling)
    * [castling_availability](#castling_availability)
    * [set_castling_availability](#set_castling_availability)
    * [en_passant](#en_passant)
    * [set_en_passant](#set_en_passant)
    * [halfmove](#halfmove)
    * [set_halfmove](#set_halfmove)
    * [fullmove](#fullmove)
    * [set_fullmove](#set_fullmove)
    * [is_mate](#is_mate)
    * [is_stalemate](#is_stalemate)
    * [is_fifty_move](#is_fifty_move)
    * [is_check](#is_check)
    * [possible_moves](#possible_moves)
    * [move](#move)
* [Move](#Move)
    * [__construct](#__construct)
    * [alg](#alg)
    * [capture](#capture)
    * [target](#target)
    * [origin_file](#origin_file)
    * [origin_rank](#origin_rank)
    * [piece](#piece)
    * [castling](#castling)
    * [promotion](#promotion)
    * [check_mate](#check_mate)
    * [annotation](#annotation)
* [NotImplementedException](#NotImplementedException)
* [ParseException](#ParseException)
* [RulesException](#RulesException)
* [Square](#Square)
    * [__construct](#__construct)
    * [rank](#rank)
    * [file](#file)
    * [alg](#alg)
    * [is_null](#is_null)
    * [n](#n)
    * [w](#w)
    * [s](#s)
    * [e](#e)
    * [nw](#nw)
    * [ne](#ne)
    * [sw](#sw)
    * [se](#se)
    * [rel](#rel)
    * [add_to](#add_to)

## Board





* Full name: \Onspli\Chess\Board


### __construct

Piece placement (from White's perspective). Each rank is described,
starting with rank 8 and ending with rank 1; within each rank,
the contents of each square are described from file "a" through file "h".

```php
Board::__construct( string pieces = &#039;rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR&#039; ): mixed
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

### export



```php
Board::export(  ): string
```





**Return Value:**





---

### square



```php
Board::square( mixed square ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `square` | **mixed** |  |


**Return Value:**





---

### square_nothrow



```php
Board::square_nothrow( mixed square ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `square` | **mixed** |  |


**Return Value:**





---

### set_square



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

### set_square_nothrow



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

### attacked_squares

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

### pieces_on_squares

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

### find

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

### copy



```php
Board::copy(  ): mixed
```





**Return Value:**





---

### active_piece



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

### opponents_piece



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

### is_check

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

### preview

Preview of the board in ASCII graphics.

```php
Board::preview(  ): string
```





**Return Value:**





---

## FEN





* Full name: \Onspli\Chess\FEN


### __construct

Load fen or setup starting position.

```php
FEN::__construct( string fen = &#039;&#039; ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `fen` | **string** |  |


**Return Value:**





---

### export

Export whole FEN string

```php
FEN::export(  ): string
```





**Return Value:**





---

### preview

Preview of the board in ASCII graphics.

```php
FEN::preview(  ): string
```





**Return Value:**





---

### board

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

### set_board



```php
FEN::set_board( mixed board ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `board` | **mixed** |  |


**Return Value:**





---

### square



```php
FEN::square( mixed square ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `square` | **mixed** |  |


**Return Value:**





---

### set_square



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

### active

Active color. "w" means White moves next, "b" means Black moves next.

```php
FEN::active(  ): string
```





**Return Value:**





---

### set_active



```php
FEN::set_active( string color ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `color` | **string** |  |


**Return Value:**





---

### castling

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

### set_castling



```php
FEN::set_castling( string castling ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `castling` | **string** |  |


**Return Value:**





---

### castling_availability



```php
FEN::castling_availability( string type ): bool
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `type` | **string** |  |


**Return Value:**





---

### set_castling_availability



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

### en_passant

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

### set_en_passant



```php
FEN::set_en_passant( mixed square ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `square` | **mixed** |  |


**Return Value:**





---

### halfmove

Halfmove clock: The number of halfmoves since the last capture or pawn
advance, used for the fifty-move rule.

```php
FEN::halfmove(  ): int
```





**Return Value:**





---

### set_halfmove



```php
FEN::set_halfmove( mixed halfmove ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `halfmove` | **mixed** |  |


**Return Value:**





---

### fullmove

Fullmove number: The number of the full move. It starts at 1, and is
incremented after Black's move.

```php
FEN::fullmove(  ): int
```





**Return Value:**





---

### set_fullmove



```php
FEN::set_fullmove( mixed fullmove ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `fullmove` | **mixed** |  |


**Return Value:**





---

### is_mate

Returns true if king of active color is in mate.

```php
FEN::is_mate(  ): bool
```





**Return Value:**





---

### is_stalemate

Returns true if king of active color is in stalemate.

```php
FEN::is_stalemate(  ): bool
```





**Return Value:**





---

### is_fifty_move

Returns true if fifty move rule draw can be claimed by active color.

```php
FEN::is_fifty_move(  ): bool
```





**Return Value:**





---

### is_check

Returns true if king of active color is in check.

```php
FEN::is_check(  ): bool
```





**Return Value:**





---

### possible_moves

Array of all possible moves in current position.

```php
FEN::possible_moves(  ): array
```





**Return Value:**





---

### move

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


### __construct



```php
Move::__construct( string move ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `move` | **string** |  |


**Return Value:**





---

### alg



```php
Move::alg(  ): string
```





**Return Value:**





---

### capture



```php
Move::capture(  ): bool
```





**Return Value:**





---

### target



```php
Move::target( bool as_object = false ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `as_object` | **bool** |  |


**Return Value:**





---

### origin_file



```php
Move::origin_file( bool as_index = false ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `as_index` | **bool** |  |


**Return Value:**





---

### origin_rank



```php
Move::origin_rank( bool as_index = false ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `as_index` | **bool** |  |


**Return Value:**





---

### piece



```php
Move::piece(  ): string
```





**Return Value:**





---

### castling



```php
Move::castling(  ): string
```





**Return Value:**





---

### promotion



```php
Move::promotion(  ): string
```





**Return Value:**





---

### check_mate



```php
Move::check_mate(  ): string
```





**Return Value:**





---

### annotation



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


### __construct



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

### rank



```php
Square::rank(  ): int
```





**Return Value:**





---

### file



```php
Square::file(  ): int
```





**Return Value:**





---

### alg



```php
Square::alg(  ): string
```





**Return Value:**





---

### is_null



```php
Square::is_null(  ): bool
```





**Return Value:**





---

### n



```php
Square::n(  ): mixed
```





**Return Value:**





---

### w



```php
Square::w(  ): mixed
```





**Return Value:**





---

### s



```php
Square::s(  ): mixed
```





**Return Value:**





---

### e



```php
Square::e(  ): mixed
```





**Return Value:**





---

### nw



```php
Square::nw(  ): mixed
```





**Return Value:**





---

### ne



```php
Square::ne(  ): mixed
```





**Return Value:**





---

### sw



```php
Square::sw(  ): mixed
```





**Return Value:**





---

### se



```php
Square::se(  ): mixed
```





**Return Value:**





---

### rel



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

### add_to



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

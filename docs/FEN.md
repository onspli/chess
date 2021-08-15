# Onspli\Chess\FEN  







## Methods

| Name | Description |
|------|-------------|
|[__construct](#fen__construct)|Load fen or setup starting position.|
|[active](#fenactive)|Active color. "w" means White moves next, "b" means Black moves next.|
|[board](#fenboard)|Piece placement (from White's perspective). Each rank is described,
starting with rank 8 and ending with rank 1; within each rank,
the contents of each square are described from file "a" through file "h".|
|[castling](#fencastling)|Castling availability. If neither side can castle, this is "-".|
|[castling_availability](#fencastling_availability)||
|[en_passant](#fenen_passant)|En passant target square in algebraic notation. If there's no en passant
target square, this is "-". If a pawn has just made a two-square move,
this is the position "behind" the pawn. This is recorded regardless of
whether there is a pawn in position to make an en passant capture.|
|[export](#fenexport)|Export whole FEN string|
|[fullmove](#fenfullmove)|Fullmove number: The number of the full move. It starts at 1, and is
incremented after Black's move.|
|[halfmove](#fenhalfmove)|Halfmove clock: The number of halfmoves since the last capture or pawn
advance, used for the fifty-move rule.|
|[is_check](#fenis_check)|Returns true if king of active color is in check.|
|[is_fifty_move](#fenis_fifty_move)|Returns true if fifty move rule draw can be claimed by active color.|
|[is_mate](#fenis_mate)|Returns true if king of active color is in mate.|
|[is_stalemate](#fenis_stalemate)|Returns true if king of active color is in stalemate.|
|[move](#fenmove)|Perform a move.|
|[possible_moves](#fenpossible_moves)|Array of all possible moves in current position.|
|[preview](#fenpreview)|Preview of the board in ASCII graphics.|
|[set_active](#fenset_active)||
|[set_board](#fenset_board)||
|[set_castling](#fenset_castling)||
|[set_castling_availability](#fenset_castling_availability)||
|[set_en_passant](#fenset_en_passant)||
|[set_fullmove](#fenset_fullmove)||
|[set_halfmove](#fenset_halfmove)||
|[set_square](#fenset_square)||
|[square](#fensquare)||




### FEN::__construct  

**Description**

```php
public __construct (void)
```

Load fen or setup starting position. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::active  

**Description**

```php
public active (void)
```

Active color. "w" means White moves next, "b" means Black moves next. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::board  

**Description**

```php
public board (void)
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

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::castling  

**Description**

```php
public castling (void)
```

Castling availability. If neither side can castle, this is "-". 

Otherwise, this has one or more letters: "K" (White can castle kingside),  
"Q" (White can castle queenside), "k" (Black can castle kingside), and/or  
"q" (Black can castle queenside). A move that temporarily prevents castling  
does not negate this notation. 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::castling_availability  

**Description**

```php
 castling_availability (void)
```

 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::en_passant  

**Description**

```php
public en_passant (void)
```

En passant target square in algebraic notation. If there's no en passant
target square, this is "-". If a pawn has just made a two-square move,
this is the position "behind" the pawn. This is recorded regardless of
whether there is a pawn in position to make an en passant capture. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::export  

**Description**

```php
public export (void)
```

Export whole FEN string 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::fullmove  

**Description**

```php
public fullmove (void)
```

Fullmove number: The number of the full move. It starts at 1, and is
incremented after Black's move. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::halfmove  

**Description**

```php
public halfmove (void)
```

Halfmove clock: The number of halfmoves since the last capture or pawn
advance, used for the fifty-move rule. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::is_check  

**Description**

```php
public is_check (void)
```

Returns true if king of active color is in check. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::is_fifty_move  

**Description**

```php
public is_fifty_move (void)
```

Returns true if fifty move rule draw can be claimed by active color. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::is_mate  

**Description**

```php
public is_mate (void)
```

Returns true if king of active color is in mate. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::is_stalemate  

**Description**

```php
public is_stalemate (void)
```

Returns true if king of active color is in stalemate. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::move  

**Description**

```php
public move (void)
```

Perform a move. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::possible_moves  

**Description**

```php
public possible_moves (void)
```

Array of all possible moves in current position. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::preview  

**Description**

```php
public preview (void)
```

Preview of the board in ASCII graphics. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::set_active  

**Description**

```php
 set_active (void)
```

 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::set_board  

**Description**

```php
 set_board (void)
```

 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::set_castling  

**Description**

```php
 set_castling (void)
```

 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::set_castling_availability  

**Description**

```php
 set_castling_availability (void)
```

 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::set_en_passant  

**Description**

```php
 set_en_passant (void)
```

 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::set_fullmove  

**Description**

```php
 set_fullmove (void)
```

 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::set_halfmove  

**Description**

```php
 set_halfmove (void)
```

 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::set_square  

**Description**

```php
 set_square (void)
```

 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### FEN::square  

**Description**

```php
 square (void)
```

 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


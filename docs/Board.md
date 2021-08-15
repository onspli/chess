# Onspli\Chess\Board  







## Methods

| Name | Description |
|------|-------------|
|[__construct](#board__construct)|Piece placement (from White's perspective). Each rank is described,
starting with rank 8 and ending with rank 1; within each rank,
the contents of each square are described from file "a" through file "h".|
|[active_piece](#boardactive_piece)||
|[attacked_squares](#boardattacked_squares)|Get array of all squares attacked (or defended) by $attacking_piece being on $attacker_square.|
|[copy](#boardcopy)||
|[export](#boardexport)||
|[find](#boardfind)|Returns array of squares containing piece.|
|[is_check](#boardis_check)|Returns true if king of active color is in check.|
|[opponents_piece](#boardopponents_piece)||
|[pieces_on_squares](#boardpieces_on_squares)|Get list of pieces on squares (including multiplicities, excluding blank squares).|
|[preview](#boardpreview)|Preview of the board in ASCII graphics.|
|[set_square](#boardset_square)||
|[set_square_nothrow](#boardset_square_nothrow)||
|[square](#boardsquare)||
|[square_nothrow](#boardsquare_nothrow)||




### Board::__construct  

**Description**

```php
public __construct (void)
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


### Board::active_piece  

**Description**

```php
 active_piece (void)
```

 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### Board::attacked_squares  

**Description**

```php
public attacked_squares (void)
```

Get array of all squares attacked (or defended) by $attacking_piece being on $attacker_square. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### Board::copy  

**Description**

```php
 copy (void)
```

 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### Board::export  

**Description**

```php
 export (void)
```

 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### Board::find  

**Description**

```php
public find (void)
```

Returns array of squares containing piece. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### Board::is_check  

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


### Board::opponents_piece  

**Description**

```php
 opponents_piece (void)
```

 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### Board::pieces_on_squares  

**Description**

```php
public pieces_on_squares (void)
```

Get list of pieces on squares (including multiplicities, excluding blank squares). 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### Board::preview  

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


### Board::set_square  

**Description**

```php
 set_square (void)
```

 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### Board::set_square_nothrow  

**Description**

```php
 set_square_nothrow (void)
```

 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### Board::square  

**Description**

```php
 square (void)
```

 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


### Board::square_nothrow  

**Description**

```php
 square_nothrow (void)
```

 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`void`


<hr />


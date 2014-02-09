Matrix
==========

Matrix class for PHP with basic operations and algorithms.

## Example

Calculation of Simple/Linear Regression:

```php
<?php

require_once 'matrix.php';

$X = MatrixFactory::fromString('
	10, 12;
	15, 10;
	20, 9;
	25, 9;
	40, 8;
	37, 8;
	43, 6;
	35, 4;
	38, 4;
	55, 5
');

$Y = MatrixFactory::fromArray(
	array(
		array(20),
		array(35),
		array(30),
		array(45),
		array(60),
		array(69),
		array(75),
		array(90),
		array(105),
		array(110)
	)
);

$I = MatrixFactory::fillMatrix(1, $X->getRowsCount(), 1);

$R = $X->insertColumn(0, $I)->T()->prod($X)->invert()->prod($X->T())->prod($Y);

echo $R->toString(), PHP_EOL;
```

Result:

```
95.469000844691;
0.81847550644385;
-7.6795362599473
```

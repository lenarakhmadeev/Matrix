<?php

// TODO : norm, eig, etc.

/**
 * Class MatrixAlgorithms
 */
abstract class MatrixAlgorithms
{

	/**
	 * Return LUP-factorization  P*A=L*U
	 *
	 * @see http://en.wikipedia.org/wiki/LUP_decomposition
	 * @see Introduction to Algorithms by Thomas H. Cormen and other
	 * @static
	 * @param Matrix $A
	 * @return array($C, $P, $singular, $even) - C = L + U - E, $singular === true for singular matrix, $even - for det
	 */
	public static function LUP($A)
	{
		if (!$A->isSquare()) {
			throw new InvalidArgumentException("Matrix must be square");
		}

		$size = $A->getRowsCount();

		$C = clone $A;
		$P = MatrixFactory::identityMatrix($size);
		$singular = false;
		$even     = true;

		for ($i = 0; $i < $size; $i++) {
			//поиск опорного элемента
			$pivotValue = 0;
			$pivot = -1;
			for ($row = $i; $row < $size; $row++) {
				if (abs($C->getElem($row, $i)) > $pivotValue) {
					$pivotValue = abs($C->getElem($row, $i));
					$pivot = $row;
				}
			}

			if ($pivotValue == 0) {
				$singular = true;
				break;
			}

			//меняем местами i-ю строку и строку с опорным элементом
			if ($pivot !== $i) {
				$P->swapRows($pivot, $i);
				$C->swapRows($pivot, $i);
				$even = !$even;
			}
			for ($j = $i + 1; $j < $size; $j++) {
				$temp = $C->getElem($j, $i) / $C->getElem($i, $i);
				$C->setElem($j, $i, $temp);
				for($k = $i + 1; $k < $size; $k++) {
					$temp = $C->getElem($j, $k) - $C->getElem($j, $i) * $C->getElem($i, $k);
					$C->setElem($j, $k, $temp);
				}
			}
		}

		return array($C, $P, $singular, $even);
	}

	/**
	 * Return determinant of matrix
	 *
	 * @static
	 * @param Matrix $matrix
	 * @return number
	 */
	public static function determinant($matrix)
	{
		list($C, , $singular, $even) = self::LUP($matrix);

		$e = $even ? 1 : -1;
		return $singular ? 0 : $e * $C->prodTrace();
	}

	// TODO : Обратная матрица
	public static function inverseMatrix($matrix)
	{

	}

	/**
	 * Return transposed matrix
	 *
	 * @static
	 * @param Matrix $matrix
	 * @return Matrix
	 */
	public static function transpose($matrix)
	{
		list($rows, $cols) = $matrix->getSize();

		$T = MatrixFactory::zeroMatrix($cols, $rows);

		for ($i = 0; $i < $rows; $i++) {
			for ($j = 0; $j < $cols; $j++) {
				$T->setElem($j, $i, $matrix->getElem($i, $j));
			}
		}

		return $T;
	}

	/**
	 * Return P-norm of matrix
	 *
	 * @static
	 * @param Matrix $matrix
	 * @param number $p
	 * @return number
	 */
	public static function pNorm($matrix, $p)
	{
		$callback = function($sum, $elem) use ($p) {
			return $sum + pow(abs($elem), $p);
		};

		$sum = $matrix->reduce($callback);

		return pow($sum, 1 / $p);
	}

	public static function mNorm()
	{



	}

	public static function lNorm()
	{

	}

}
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
		if (!Matrix::isMatrix($A)) {
			throw new InvalidArgumentException('Matrix needed. Given' . gettype($A));
		}

		if (!$A->isSquare()) {
			throw new InvalidArgumentException("Matrix must be square");
		}

		$size = $A->getRowsCount();

		$C = clone $A;
		$P = array();
		for ($i = 0; $i < $size; $i++) {
			$P[$i] = $i;
		}
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
				list($P[$i], $P[$pivot]) = array($P[$pivot], $P[$i]);
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
	 *
	 * @static
	 * @param Matrix $C
	 * @param Matrix $P
	 * @param Matrix $b
	 * @return Matrix
	 */
	public static function LUP_solve($C, $P, $b)
	{
		// TODO : Проверка входных

		$n = $C->getRowsCount();

		$y = array();
		for ($i = 0; $i < $n; $i++) {
			$sum = 0;
			for ($j = 0; $j < $i; $j++) {
				$sum += $C->getElem($i, $j) * $y[$j];
			}
			$y[$i] = $b->getElem($P[$i], 0) - $sum;
		}

		$x = array();
		for ($i = $n - 1; $i >= 0; $i--) {
			$sum = 0;
			for ($j = $i + 1; $j < $n; $j++) {
				$sum += $C->getElem($i, $j) * $x[$j];
			}
			$x[$i] = ($y[$i] - $sum) / $C->getElem($i, $i);
		}

		return MatrixFactory::fromArray($x)->T();
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

	/**
	 * Return inverse matrix
	 *
	 * @static
	 * @param Matrix $matrix
	 * @return Matrix
	 * @throws LogicException
	 */
	public static function inverseMatrix($matrix)
	{
		list($C, $P, $singular) = self::LUP($matrix);

		if ($singular) {
			throw new LogicException("Matrix is singular. Can't find inverse");
		}

		$size = $matrix->getRowsCount();
		$inverse = MatrixFactory::zeroMatrix($size);
		for ($i = 0; $i < $size; $i++) {
			$b = MatrixFactory::zeroMatrix($size, 1);
			$b->setElem($i, 0, 1);
			$value = self::LUP_solve($C, $P, $b);
			$inverse->setColumn($i, $value);
		}

		return $inverse;
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
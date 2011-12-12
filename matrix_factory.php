<?php

// TODO : генерация известных матриц

/**
 * Generator of matrices
 *
 */
abstract class MatrixFactory
{

	/**
	 * Return matrix from bidimensional array
	 *
	 * @static
	 * @param array $array
	 * @return Matrix
	 */
	public static function fromArray($array)
	{
		return new Matrix($array);
	}

	/**
	 * Return matrix with ones on the main diagonal and zeros elsewhere
	 *
	 * @static
	 * @param int $rows - size of matrix
	 * @param int|nul $cols - for non-square matrix
	 * @return Matrix
	 */
	public static function identityMatrix($rows, $cols = null)
	{
		$cols = $cols ?: $rows;

		$array = array();
		for ($i = 0; $i < $rows; $i++) {
			for ($j = 0; $j < $cols; $j++) {
				if ($i === $j) {
					$array[$i][$j] = 1;
				} else {
					$array[$i][$j] = 0;
				}
			}
		}

		return self::fromArray($array);
	}

	/**
	 * Return matrix filled with $fill
	 *
	 * @static
	 * @param number $filler - number for fill
	 * @param int $rows - size of matrix
	 * @param int|null $cols - for non-square matrix
	 * @return Matrix
	 */
	public static function fillMatrix($filler, $rows, $cols = null)
	{
		$cols = $cols ?: $rows;

		$array = array();
		for ($i = 0; $i < $rows; $i++) {
			for ($j = 0; $j < $cols; $j++) {
				$array[$i][$j] = $filler;
			}
		}

		return self::fromArray($array);
	}

	/**
	 * Return zeros matrix
	 *
	 * @static
	 * @param int $rows - size of matrix
	 * @param int|nul $cols - for non-square matrix
	 * @return Matrix
	 */
	public static function zeroMatrix($rows, $cols = null)
	{
		return self::fillMatrix(0, $rows, $cols);
	}

	/**
	 * Return generated from special string
	 *
	 * <pre>
	 * '1, 2, 3;
	 * 4, 5, 6' => array(
	 * 	array(1, 2, 3),
	 * 	array(4, 5, 6)
	 * )
	 * </pre>
	 *
	 * @static
	 * @param $str - string with matrix definition
	 * @return Matrix
	 */
	public static function fromString($str)
	{
		$array = array();
		$rows = explode(';', $str);
		foreach ($rows as $row) {
			$r = explode(',', $row);
			$r = array_map('trim', $r);
			$array[] = $r;
		}

		return self::fromArray($array);
	}
}
<?php

/**
 * Class Matrix
 */

require_once 'matrix_algorithms.php';
require_once 'matrix_factory.php';

class Matrix
{

	/**
	 * @var  array - matrix container
	 */
	protected $matrix;

	/**
	 * Construct matrix from array of clone matrix
	 *
	 * @param array|Matrix $data
	 */
	public function __construct(&$data)
	{
		if (Matrix::isMatrix($data)) {
			return self::fromArray($data->getArray());
		}

		return self::fromArray($data);
	}

	/**
 	 * Construct matrix from array
	 *
	 * @param array $array
	 * @throws InvalidArgumentException
	 */
	protected function fromArray(&$array)
	{
		if (!is_array($array)) {
			throw new InvalidArgumentException('Array needed. Given' . gettype($array));
		}

		// Если вектор-строка
		if (!is_array($array[0])) {
			$array = array($array);
		}

		$rows = count($array);
		$cols = count($array[0]);
		for ($row = 1; $row < $rows; $row++) {
			if ($cols !== count($array[$row])) {
				throw new InvalidArgumentException('Array has different rows');
			}
		}

		$matrix = array();

		for ($i = 0; $i < $rows; $i++) {
			for ($j = 0; $j < $cols; $j++) {
				$matrix[$i][$j] = (float) $array[$i][$j];
			}
		}

		$this->matrix = $matrix;
	}

	///

	/**
	 * Return array-container of matrix
	 *
	 * @return array
	 */
	public function getArray()
	{
		return $this->matrix;
	}

	/**
	 * Return element
	 *
	 * @param $row
	 * @param $col
	 * @return number
	 * @throws OutOfRangeException
	 */
	public function getElem($row, $col)
	{
		list($rows, $cols) = $this->getSize();

		if ($row < $rows && $col < $cols) {
			return $this->matrix[$row][$col];
		} else {
			throw new OutOfRangeException("Invalid offset. row = $row must be lover $rows; col = $col must be lover $cols;");
		}
	}

	/**
	 * Set element of matrix
	 *
	 * @param $row
	 * @param $col
	 * @param number $value
	 * @return array
	 * @throws OutOfRangeException
	 */
	public function setElem($row, $col, $value)
	{
		list($rows, $cols) = $this->getSize();

		if ($row < $rows && $col < $cols) {
			return $this->matrix[$row][$col] = (float) $value;
		} else {
			throw new OutOfRangeException("Invalid offset. row = $row must be lower $rows; col = $col must be lower $cols");
		}
	}

	/**
	 * Return column
	 *
	 * @param $col
	 * @param bool $return_array
	 * @return array|Matrix
	 * @throws OutOfRangeException
	 */
	public function getColumn($col, $return_array = false)
	{
		list($rows, $cols) = $this->getSize();

		if ($col >= $cols) {
			throw new OutOfRangeException("Invalid offset. col = $col must be lower $cols");
		}

		$res = array();
		for ($row = 0; $row < $rows; $row++) {
			$res[$row] = $this->getElem($row, $col);
		}

		if ($return_array) {
			return $res;
		} else {
			$res = new Matrix($res);
			return $res->T();
		}
	}

	/**
	 * Return this matrix with setted column
	 *
	 * @param int $col
	 * @param Matrix $value
	 * @return Matrix
	 * @throws InvalidArgumentException|OutOfRangeException
	 */
	public function setColumn($col, $value)
	{
		if (!Matrix::isMatrix($value)) {
			throw new InvalidArgumentException('Argument $value must be matrix. '
				. gettype($value) . ' given');
		}

		if (($rows = $this->getRowsCount()) !== $value->getRowsCount()
			|| $value->getColsCount() !== 1) {
			throw new OutOfRangeException("Count of rows must equals");
		}

		for ($row = 0; $row < $rows; $row++) {
			$this->setElem($row, $col, $value->getElem($row, 0));
		}

		return $this;
	}

	/**
	 * Return row
	 *
	 * @param $row
	 * @param bool $return_array
	 * @return array|Matrix
	 * @throws OutOfRangeException
	 */
	public function getRow($row, $return_array = false)
	{
		list($rows, $cols) = $this->getSize();

		if ($row >= $rows) {
			throw new OutOfRangeException("Invalid offset. row = $row must be lower $rows");
		}

		$res = array();
		for ($col = 0; $col < $cols; $col++) {
			$res[$col] = $this->getElem($row, $col);
		}

		return $return_array ? $res : new Matrix($res);
	}

	// TODO : asd

	public function setRow($row, $value)
	{
		if (!Matrix::isMatrix($value)) {
			throw new InvalidArgumentException('Argument $value must be matrix. '
				. gettype($value) . ' given');
		}

		if (($cols = $this->getColsCount()) !== $value->getColsCount()
			|| $value->getRowsCount() !== 1) {
			throw new OutOfRangeException("Count of cols must equals");
		}

		for ($col = 0; $col < $cols; $col++) {
			$this->setElem($row, $col, $value->getElem(0, $col));
		}

		return $this;
	}

	/**
	 * Return main diagonal
	 *
	 * @param bool $return_array - вернуть массивом
	 * @return array|Matrix
	 */
	public function getDiagonal($return_array = false)
	{
		$diagonal = array();
		$matrix   = $this->getArray();
		$size     = $this->getSize();
		$n        = min($size);

		for ($i = 0; $i < $n; $i++) {
			$diagonal[$i] = $matrix[$i][$i];
		}

		return $return_array ? $diagonal : new Matrix($diagonal);
	}

	/**
	 * Return size of matrix
	 *
	 * @return array($rows, $cols)
	 */
	public function getSize()
	{
		$rows = count($this->matrix);
		$cols = count($this->matrix[0]);

		return array($rows, $cols);
	}

	/**
	 * Return rows count
	 *
	 * @return int
	 */
	public function getRowsCount()
	{
		return count($this->matrix);
	}

	/**
	 * Return columns count
	 *
	 * @return int
	 */
	public function getColsCount()
	{
		return count($this->matrix[0]);
	}

	///

	// TODO : Проверки


	/**
	 * Return matrix with swapped columns
	 *
	 * @param int $col1
	 * @param int $col2
	 * @return Matrix
	 * @throws OutOfRangeException
	 */
	public function swapColumns($col1, $col2)
	{
		$cols = $this->getColsCount();

		if ($col1 >= $cols || $col2 >= $cols) {
			throw new OutOfRangeException("Cols $col1, $col2 must be lower than $cols");
		}

		$col1_value = $this->getColumn($col1);
		$col2_value = $this->getColumn($col2);
		$new_matrix = clone $this;
		$new_matrix->setColumn($col1, $col2_value);
		$new_matrix->setColumn($col2, $col1_value);

		return $new_matrix;
	}

	public function swapRows($row1, $row2)
	{
		 list($this->matrix[$row1], $this->matrix[$row2]) =
		array($this->matrix[$row2], $this->matrix[$row1]);
	}

	public function insertColumn($col1, $col2)
	{

	}

	public function insertRow($row1, $row2)
	{

	}

	public function deleteColumn($col)
	{

	}

	public function deleteRow($row)
	{

	}

	function swapRowCol ($row, $col)
	{

	}

	/// Matrix operations

	/**
	 * Return transposed matrix
	 *
	 * @return Matrix
	 */
	public function T()
	{
		return MatrixAlgorithms::transpose($this);
	}

	/**
	 * Return determinant of matrix
	 *
	 * @return number
	 */
	public function det()
	{
		return MatrixAlgorithms::determinant($this);
	}

	/**
	 * Return inverse matrix
	 *
	 * @return Matrix
	 */
	public function invert()
	{
		return MatrixAlgorithms::inverseMatrix($this);
	}

	/**
	 * Return  Euclidean norm
	 *
	 * @return number
	 */
	public function EuclideanNorm()
	{
		return MatrixAlgorithms::pNorm($this, 2);
	}

	/**
	 * Return sum of elements on main diagonal
	 *
	 * @return number
	 */
	public function trace()
	{
		return array_sum($this->getDiagonal(true));
	}

	/**
	 * Return product of elements on main diagonal
	 *
	 * @return number
	 */
	public function prodTrace()
	{
		return array_product($this->getDiagonal(true));
	}

	/**
	 * Return rounded matrix
	 *
	 * @param int $decimals
	 * @return Matrix
	 */
	public function round($decimals = 0)
	{
		$callback = function ($elem) use ($decimals) {
			return round($elem, $decimals);
		};

		return $this->map($callback);
	}

	// Binary operations

	/**
	 * Return sum of matrices
	 *
	 * @param  Matrix $B
	 * @return Matrix
	 * @throws InvalidArgumentException
	 */
	public function add($B)
	{
		return $this->elemBinaryOperation($B, function($a, $b){return $a + $b;});
	}

	/**
	 * Return sum of matrices
	 *
	 * @param Matrix $B
	 * @return Matrix
	 * @throws InvalidArgumentException
	 */
	public function sub($B)
	{
		return $this->elemBinaryOperation($B, function($a, $b){return $a - $b;});
	}

	/**
	 * Return product of elements
	 *
	 * @param Matrix $B
	 * @return Matrix
	 */
	public function elemProd($B)
	{
		return $this->elemBinaryOperation($B, function($a, $b){return $a * $b;});
	}

	/**
	 * Return division of elements
	 *
	 * @param Matrix $B
	 * @return Matrix
	 */
	public function elemDiv($B)
	{
		return $this->elemBinaryOperation($B, function($a, $b){return $a / $b;});
	}

	/**
	 * Return matrix applied $operation
	 *
	 * @param Matrix $B
	 * @param $operation
	 * @return Matrix
	 * @throws InvalidArgumentException
	 */
	public function elemBinaryOperation(&$B, $operation)
	{
		if (!Matrix::isMatrix($B)) {
			throw new InvalidArgumentException('Matrix needed. Given' . gettype($B));
		}

		list($rows, $cols) = $this->getSize();
		if (array($rows, $cols) !== $B->getSize()) {
			throw new InvalidArgumentException('Invalid size of matrices');
		}

		$res = MatrixFactory::zeroMatrix($rows, $cols);
		for ($row = 0; $row < $rows; $row++) {
			for ($col = 0; $col < $cols; $col++) {
				$temp = $operation($this->getElem($row, $col), $B->getElem($row, $col));
				$res->setElem($row, $col, $temp);
			}
		}

		return $res;
	}

	/**
	 * Return product of matrices
	 *
	 * @param Matrix|number $B
	 * @return Matrix
	 * @throws InvalidArgumentException
	 */
	public function prod($B)
	{
		// Умножение на число
		if (is_numeric($B)) {
			list($rows, $cols) = $this->getSize();
			$new_matrix = MatrixFactory::zeroMatrix($rows, $cols);

			for ($row = 0; $row < $rows; $row++) {
				for ($col = 0; $col < $cols; $col++) {
					$temp = $this->getElem($row, $col) * $B;
					$new_matrix->setElem($row, $col, $temp);
				}
			}

			return $new_matrix;
		}

		if (!Matrix::isMatrix($B)) {
			throw new InvalidArgumentException("Argument must be Matrix or number");
		}

		list($rows1, $cols1) = $this->getSize();
		list($rows2, $cols2) = $B->getSize();
		if ($cols1 !== $rows2) {
			throw new InvalidArgumentException("Invalid size of matrices");
		}

		$new_matrix = MatrixFactory::zeroMatrix($rows1, $cols2);
		for ($row = 0; $row < $rows1; $row++) {
			for ($col = 0; $col < $cols2; $col++) {
				$sum = 0;
				for ($k = 0; $k < $cols1; $k++) {
					$sum += $this->getElem($row, $k) * $B->getElem($k, $col);
				}

				$new_matrix->setElem($row, $col, $sum);
			}
		}

		return $new_matrix;
	}

	/**
	 * Return $this * $B^-1
	 *
	 * @param Matrix $B
	 * @return Matrix
	 */
	public function div($B)
	{
		return $this->prod($B->invert());
	}

	/// Utils

	/**
	 * Return true if matrices is equals
	 *
	 * @param Matrix $matrix
	 * @param float $eps
	 * @return bool
	 * @throws InvalidArgumentException
	 */
	public function equals($matrix, $eps = 0.0)
	{
		if (!Matrix::isMatrix($matrix)) {
			throw new InvalidArgumentException('Matrix needed. Given' . gettype($matrix));
		}

		if ($eps === 0) {
			return $this->getArray() == $matrix->getArray();
		} else {
			$sub = $this->sub($matrix);
			return $sub->EuclideanNorm() < $eps;
		}
	}

	/**
	 * Return is matrix
	 *
	 * @static
	 * @param $matrix
	 * @return bool
	 */
	public static function isMatrix($matrix)
	{
		return is_a($matrix, __CLASS__);
	}

	/**
	 * Return is square
	 *
	 * @return bool
	 */
	public function isSquare()
	{
		list($rows, $cols) = $this->getSize();

		return $rows === $cols;
	}

	// TODO :  max, min, sort, etc.

	/**
	 * Return true if elems return true from $callback, false else
	 *
	 * @param $callback
	 * @return bool
	 */
	public function all($callback)
	{
		list($rows, $cols) = $this->getSize();
		for ($row = 0; $row < $rows; $row++) {
			for ($col = 0; $col < $cols; $col++) {
				if (!$callback($this->getElem($row, $col))) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Map for matrix
	 *
	 * @param $callback
	 * @return Matrix
	 */
	public function map($callback)
	{
		$res = clone $this;

		list($rows, $cols) = $res->getSize();
		for ($row = 0; $row < $rows; $row++) {
			for ($col = 0; $col < $cols; $col++) {
				$temp = $callback($res->getElem($row, $col));
				$res->setElem($row, $col, $temp);
			}
		}

		return $res;
	}

	/**
	 * Return abs matrix
	 *
	 * @return Matrix
	 */
	public function abs()
	{
		$callback = function ($elem) {
			return abs($elem);
		};

		return $this->map($callback);
	}

	/**
	 * Reduce for matrix
	 *
	 * @param $callback
	 * @param float $initial
	 * @return number
	 */
	public function reduce($callback, $initial = 0.0)
	{
		$rows = $this->getRowsCount();
		for ($row = 0; $row < $rows; $row++) {
			$initial = array_reduce($this->getRow($row, true), $callback, $initial);
		}

		return $initial;
	}

	/**
	 * Return sum of all elements
	 *
	 * @return number
	 */
	public function sumAll()
	{
		$callback = function($sum, $elem) {
			return $sum + $elem;
		};

		return $this->reduce($callback);
	}

	/**
	 * Return product of elements of matrix
	 *
	 * @return number
	 */
	public function prodAll()
	{
		$callback = function($acc, $elem) {
			return $acc * $elem;
		};

		return $this->reduce($callback, 1);
	}

	/**
	 * Return string representation of matrix
	 *
	 * @param string $col_div
	 * @param string $row_div
	 * @return string
	 */
	public function toString($col_div = ", ", $row_div = ";\n")
	{
		$rows_arr = array();
		$rows = $this->getRowsCount();
		for ($i = 0; $i < $rows; $i++) {
			$rows_arr[] = implode($col_div, $this->getRow($i, true));
		}

		return implode($row_div, $rows_arr);
	}

	/**
	 * Return string representation of matrix
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}
}
<?php

require_once __DIR__ . '/../matrix.php';

/**
 * Test class for Matrix.
 * Generated by PHPUnit on 2011-12-11 at 00:10:46.
 */
class MatrixManipulationTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Matrix
	 */
	public $A;
	/**
	 * @var Matrix
	 */
	public $B;

	public function setUp()
	{
		$this->A = MatrixFactory::fromString(
			'1, 2, 3;
			 3, 4, 5'
		);

		$this->B = MatrixFactory::fromString(
			'8, 9;
			11, 12'
		);
	}

	public function testGetColumn()
	{
		$col = MatrixFactory::fromString('2; 4');

		$this->assertEquals($col->getArray(), $this->A->getColumn(1, false)->getArray());
	}

	public function testGetColumnAsArray()
	{
		$arr = array(3, 5);

		$this->assertEquals($arr, $this->A->getColumn(2, true));
	}

	public function testSetColumn()
	{
		$value = MatrixFactory::fromString(
			'5; 5'
		);

		$expect = MatrixFactory::fromString(
			'1, 5, 3;
			 3, 5, 5'
		);

		$this->assertEquals($expect->getArray(), $this->A->setColumn(1, $value)->getArray());
	}

	/**
	 * @expectedException OutOfRangeException
	 */
	public function testSetInvalidColumn()
	{
		$value = MatrixFactory::fromString(
			'1; 2; 3'
		);

		$this->A->setColumn(2, $value);
	}

	/**
	 * @expectedException OutOfRangeException
	 */
	public function testSetColumnMustBeColumn()
	{
		$value = MatrixFactory::fromString(
			'1, 2; 2, 4'
		);

		$this->A->setColumn(2, $value);
	}

	public function testGetRow()
	{
		$row = MatrixFactory::fromString('1, 2, 3');

		$this->assertEquals($row->getArray(), $this->A->getRow(0, false)->getArray());
	}

	public function testGetRowAsArray()
	{
		$arr = array(3, 4, 5);

		$this->assertEquals($arr, $this->A->getRow(1, true));
	}

	public function testSetRow()
	{

	}

	public function testGetDiagonal()
	{
		$d = array(1, 4);

		$this->assertEquals($d, $this->A->getDiagonal(true));
	}

	public function testGetDiagonalAsArray()
	{
		$d = MatrixFactory::fromString('1, 4');

				$this->assertEquals($d->getArray(), $this->A->getDiagonal(false)->getArray());
	}

	public function testSwapColumns()
	{
		$C = MatrixFactory::fromString(
			'1, 3, 2;
			 3, 5, 4'
		);

		$this->assertEquals($C->getArray(), $this->A->swapColumns(1, 2)->getArray());
	}

	public function testDeleteColumn()
	{
		$value = MatrixFactory::fromString(
			'1, 2, 3; 4, 5, 6; 7, 8, 9'
		);

		$expect = MatrixFactory::fromString(
			'1, 3; 4, 6; 7, 9'
		);

		$this->assertEquals($expect->getArray(), $value->deleteColumn(1)->getArray());
	}

	public function testDeleteRow()
	{
		$value = MatrixFactory::fromString(
			'1, 2, 3; 4, 5, 6; 7, 8, 9'
		);

		$expect = MatrixFactory::fromString(
			'1, 2, 3; 7, 8, 9'
		);

		$this->assertEquals($expect->getArray(), $value->deleteRow(1)->getArray());
	}

	public function testSwapRowCol()
	{
		$value = MatrixFactory::fromString(
			'1, 2, 3; 4, 5, 6; 7, 8, 9'
		);

		$expect = MatrixFactory::fromString(
			'1, 4, 3; 2, 5, 8; 7, 6, 9'
		);

		$this->assertEquals($expect->getArray(), $value->swapRowCol(1, 1)->getArray());
	}
}

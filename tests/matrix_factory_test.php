<?php

require_once __DIR__ . '/../matrix.php';

/**
 * Test class for MatrixFactory.
 */
class MatrixFactoryTest extends PHPUnit_Framework_TestCase
{

	public function testFromArray()
	{
		$array = array(
			array(
				1.0, 23, 5,
			),
			array(
				3, '4', '1.0'
			),
		);
		$expected = array(
			array(
				1.0, 23.0, 5.0,
			),
			array(
				3.0, 4.0, 1.0
			),
		);
		$matrix = MatrixFactory::fromArray($array);

		$this->assertEquals($expected, $matrix->getArray());
	}

	public function testMatrixFromOneColumnArray()
	{
		$array = array(
			array(
				1.0
			),
			array(
				3
			),
		);
		$matrix = MatrixFactory::fromArray($array);

		$this->assertEquals($array, $matrix->getArray());
	}

	public function testMatrixFromOneRowArray()
	{
		$array = array(1, 2, 3);
		$matrix = MatrixFactory::fromArray($array);

		$this->assertEquals(array($array), $matrix->getArray());
	}

	public function testIdentityMatrix()
	{
		$expected = array(
			array(1.0, 0.0, 0.0),
			array(0.0, 1.0, 0.0),
			array(0.0, 0.0, 1.0),
		);

		$this->assertEquals($expected, MatrixFactory::identityMatrix(3)->getArray());

		//
		$expected = array(
			array(1.0, 0.0, 0.0),
			array(0.0, 1.0, 0.0),
		);

		$this->assertEquals($expected, MatrixFactory::identityMatrix(2, 3)->getArray());
	}

	public function testFillMatrix()
	{
		$expected = array(
			array(7, 7, 7),
			array(7, 7, 7),
			array(7, 7, 7),
		);

		$this->assertEquals($expected, MatrixFactory::fillMatrix(7, 3)->getArray());

		//
		$expected = array(
			array(11, 11, 11),
			array(11, 11, 11),
		);

		$this->assertEquals($expected, MatrixFactory::fillMatrix(11, 2, 3)->getArray());
	}

	public function testZeroMatrix()
	{
		$expected = array(
			array(0.0, 0.0, 0.0),
			array(0.0, 0.0, 0.0),
			array(0.0, 0.0, 0.0),
		);

		$this->assertEquals($expected, MatrixFactory::zeroMatrix(3)->getArray());

		//
		$expected = array(
			array(0.0, 0.0, 0.0),
			array(0.0, 0.0, 0.0),
		);

		$this->assertEquals($expected, MatrixFactory::zeroMatrix(2, 3)->getArray());
	}

	public function testMatrixFromString()
	{
		$str = '
			1, 2, 4;
			5,6,8;3, 1,

			1
		';

		$expected = array(
			array(1, 2, 4),
			array(5, 6, 8),
			array(3, 1, 1)
		);

		$this->assertEquals($expected, MatrixFactory::fromString($str)->getArray());

		//
		$str = '  1;5; 3';

		$expected = array(
			array(1),
			array(5),
			array(3)
		);

		$this->assertEquals($expected, MatrixFactory::fromString($str)->getArray());
	}
}
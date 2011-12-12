<?php

require_once __DIR__ . '/../matrix.php';

/**
 * Test class for MatrixAlgorithms.
 */
class MatrixAlgorithmsTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var  Matrix
	 */
	public $A;
	/**
	 * @var  Matrix
	 */
	public $sin_A;

	public function setUp()
	{
		$this->A = MatrixFactory::fromString(
			'1, 5, 4;
			 2, 2, 2;
			 5, 5, 1'
		);

		$this->sin_A = MatrixFactory::fromString(
			'1, 2;
			 1, 2'
		);
	}

	public function testLUP()
	{
		$expC = MatrixFactory::fromString(
			' 5,  5,   1;
			0.2,  4, 3.8;
			0.4,  0, 1.6'
		);
		$expP = array(2, 0, 1);

		list($C, $P, $singular, $even) = MatrixAlgorithms::LUP($this->A);

		$this->assertEquals($expC->getArray(), $C->getArray());
		$this->assertEquals($expP, $P);
		$this->assertTrue(!$singular);
		$this->assertEquals(true, $even);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testLUPNonSquare()
	{
		$A = MatrixFactory::fromString('1, 2');

		MatrixAlgorithms::inverseMatrix($A);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testLUPNonMatrix()
	{
		MatrixAlgorithms::inverseMatrix(1);
	}

	public function testLUP_solve()
	{
		$A = MatrixFactory::fromString('1, 2; 3, 4');
		$b = MatrixFactory::fromString('4;5');
		$x = MatrixFactory::fromString('-3; 3.5');

		list($C, $P) = MatrixAlgorithms::LUP($A);
		$this->assertEquals($x, MatrixAlgorithms::LUP_solve($C, $P, $b));
	}

	public function testSingularMatrixLUP()
	{
		list($C, $P, $singular) = MatrixAlgorithms::LUP($this->sin_A);

		$this->assertTrue($singular);
	}

	public function testDeterminant()
	{
		$this->assertEquals(32, MatrixAlgorithms::determinant($this->A));

		$A = MatrixFactory::fromString(
			'1, 2;
			 3, 4'
		);

		$this->assertEquals(-2, MatrixAlgorithms::determinant($A));
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testDetNonSquare()
	{
		$A = MatrixFactory::fromString(
			'1, 2, 3;
			 3, 4, 5'
		);

		MatrixAlgorithms::determinant($A);
	}

	public function testSingularMatrixDet()
	{
		$this->assertEquals(0, MatrixAlgorithms::determinant($this->sin_A));
	}

	public function testInverseMatrix()
	{
		$A = MatrixFactory::fromString(
			'1, 2;
			 1, 1'
		);

		$expInv = MatrixFactory::fromString(
			'-1,  2;
			  1, -1'
		);

		$inv = MatrixAlgorithms::inverseMatrix($A);

		$this->assertEquals($expInv->getArray(), $inv->getArray());
	}

	/**
	 * @expectedException LogicException
	 */
	public function testSingularMatrixInverse()
	{
		$A = MatrixFactory::fromString(
			'1, 2;
			 1, 2'
		);

		MatrixAlgorithms::inverseMatrix($A);
	}

	public function testTranspose()
	{
		$trans_A = MatrixFactory::fromString(
			'1, 2, 5;
			 5, 2, 5;
			 4, 2, 1'
		);

		$this->assertEquals($trans_A->getArray(), MatrixAlgorithms::transpose($this->A)->getArray());
	}

	public function testPnorm()
	{

	}
}
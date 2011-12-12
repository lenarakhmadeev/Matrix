<?php

require_once '/apps/test/matrix/matrix.php';

/**
 * Test class for Matrix.
 */
class MatrixTest extends PHPUnit_Framework_TestCase
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
			'1, 2;
			 3, 4'
		);
	}

	// TODO : копирование, из масива, и неправ массива

	public function testConstruct()
	{

	}

	public function testGetArray()
	{
		$arr = array(
			array(1, 2, 3),
			array(3, 4, 5)
		);

		$this->assertEquals($arr, $this->A->getArray());
	}

	public function testGetElem()
	{
		$this->assertEquals(5, $this->A->getElem(1, 2));
	}

	/**
	 * @expectedException OutOfRangeException
	 */
	public function testGetElemInvalidOffset()
	{
		$this->A->getElem(1, 3);
	}

	public function testSetElem()
	{
		$A = $this->A;
		$A->setElem(1, 2, 123);

		$this->assertEquals(123, $A->getElem(1, 2));
	}

	/**
	 * @expectedException OutOfRangeException
	 */
	public function testSetElemInvalidOffset()
	{
		$this->A->setElem(1, 3, 123);
	}

	public function testGetSize()
	{
		$this->assertEquals(array(2, 3), $this->A->getSize());
	}

	public function testGetRowsCount()
	{
		$this->assertEquals(2, $this->A->getRowsCount());
	}

	public function testGetColsCount()
	{
		$this->assertEquals(3, $this->A->getColsCount());
	}

	public function testMatrixTranspose()
	{
		$T = MatrixFactory::fromString(
			'1, 3;
			 2, 4;
			 3, 5'
		);

		$this->assertEquals($T->getArray(), $this->A->T()->getArray());
	}

	public function testDet()
	{
		$this->assertEquals(-2, $this->B->det());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testNonSquareDet()
	{
		$this->A->det();
	}

	public function testRound()
	{
		$a = MatrixFactory::fromString(
			'1.115,  0.0012;
			 2,    999.123456'
		);

		$b = MatrixFactory::fromString(
			'1.12,  0;
			 2,    999.12'
		);

		$this->assertEquals($b->getArray(), $a->round(2)->getArray());
	}

	public function testIsMatrix()
	{
		$matrix = MatrixFactory::identityMatrix(3);
		$this->assertTrue(Matrix::isMatrix($matrix));

		$not_matrix = array(1, 2, 3);
		$this->assertTrue(!Matrix::isMatrix($not_matrix));

		$not_matrix = new stdClass();
		$this->assertTrue(!Matrix::isMatrix($not_matrix));
	}

	public function testIsSquare()
	{
		$this->assertTrue(!$this->A->isSquare());
		$this->assertTrue($this->B->isSquare());
	}

	public function testToString()
	{
		$str = "1, 2, 3; 4, 5, 6";

		$this->assertEquals($str, MatrixFactory::fromString($str)->toString(', ', '; '));
	}

	public function testAll()
	{
		$callback = function ($elem) {
			return $elem < 10;
		};

		$this->assertTrue($this->A->all($callback));

		$callback = function ($elem) {
			return $elem < 4;
		};

		$this->assertTrue(!$this->A->all($callback));
	}

	public function testAbs()
	{
		$a = MatrixFactory::fromString(
			'-11,     12;
			   0, -0.001'
		);

		$abs_a = MatrixFactory::fromString(
			'11, 12;
			0, 0.001'
		);

		$this->assertEquals($abs_a->getArray(), $a->abs()->getArray());
	}

	public function testSumAll()
	{
		$this->assertEquals(18, $this->A->sumAll());
	}

	public function testProdAll()
	{
		$this->assertEquals(24, $this->B->prodAll());
	}

	public function testReduce()
	{
		$callback = function($sum, $elem) {
			return $sum + $elem;
		};

		$this->assertEquals(18, $this->A->reduce($callback));
	}

	public function testMap()
	{
		$callback = function($elem) {
			return $elem * 2;
		};

		$c = MatrixFactory::fromString(
			'2, 4;
			 6, 8'
		);

		$this->assertEquals($c->getArray(), $this->B->map($callback)->getArray());
	}
}


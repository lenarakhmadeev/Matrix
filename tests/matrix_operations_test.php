<?php

require_once __DIR__ . '/../matrix.php';

/**
 * Test class for Matrix.
 */
class MatrixOperationsTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Matrix
	 */
	public $A;
	/**
	 * @var Matrix
	 */
	public $B;
	/**
	 * @var Matrix
	 */
	public $X;
	/**
	 * @var Matrix
	 */
	public $Y;


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

		$this->X = MatrixFactory::fromArray(
			array(
				array(1, 2, 0),
				array(4, 6, 0),
			)
		);

		$this->Y = MatrixFactory::fromArray(
			array(
				array(3, 1, 1),
				array(7, 12, 2),
			)
		);
	}


	// TODO : тесты на почленные операции

	public function testAddMatrices()
	{
		$c = MatrixFactory::fromArray(
			array(
				array(4, 3, 1),
				array(11, 18, 2),
			)
		);

		$this->assertEquals($c->getArray(), $this->X->add($this->Y)->getArray());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testAddSthElse()
	{
		$this->A->add(new stdClass());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testAddSizeFail()
	{
		$this->A->add($this->B);
	}

	public function testSubMatrices()
	{
		$c = MatrixFactory::fromArray(
			array(
				array(-2, 1, -1),
				array(-3, -6, -2),
			)
		);

		$this->assertEquals($c->getArray(), $this->X->sub($this->Y)->getArray());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSubSthElse()
	{
		$this->A->sub(new stdClass());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSubSizeFail()
	{
		$this->A->sub($this->B);
	}

	public function testProd()
	{
		$a = $this->X;
		$b = $this->Y->T();

		$c = MatrixFactory::fromString(
			'5,  31;
			18, 100'
		);

		$this->assertEquals($c->getArray(), $a->prod($b)->getArray());
	}

	public function testProdNumber()
	{
		$c = MatrixFactory::fromString(
			'3, 6;
			 9, 12'
		);

		$this->assertEquals($c->getArray(), $this->B->prod(3)->getArray());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testProdSthElse()
	{
		$this->B->prod(new stdClass());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testProdSizeFail()
	{
		$this->X->prod($this->Y);
	}

	public function testDiv()
	{
		$a = $this->B;

		$this->assertEquals(MatrixFactory::identityMatrix(2)->getArray(), $a->div($a)->getArray());
	}

}
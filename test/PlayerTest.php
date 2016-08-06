<?php
require_once 'PHPUnit\Autoload.php';

//use PHPUnit\Framework\TestCase;

class PlayerTest extends PHPUnit_Framework_TestCase
{
	protected $p1;
	protected $name;
	private $resultArray;
	
    protected function setUp()
    {
        // Arrange
		$this->name = "Kalle";
		$this->resultArray = array(1,2,3,4,5,6);
		$this->p1 = new Player($this->name,$this->resultArray);
	}
	
	/**
     * @covers   \obanTour\Player::getBestResult
     * @uses     \obanTour\Player::__construct
     */
	public function testGetBestResult()
	{
		$this->assertEquals(18, $this->p1->getBestResult(4));
		$this->assertEquals(11, $this->p1->getBestResult(2));
	}
	
	public function testGetName()
	{
		$this->assertEquals($this->name, $this->p1->getName());
	}

	public function testGetResult()
	{
		$i=0;
		foreach ($this->resultArray as $r) {
			$this->assertEquals($r, $this->p1->getResult($i++));
		}
	}
	
	public function testGetTableString()
	{
		$this->assertEquals($this->name, $this->p1->getTableString());
	}
}
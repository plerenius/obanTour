<?php

use PHPUnit\Framework\TestCase;

class PlayerTest extends PHPUnit_Framework_TestCase
{
	protected $p1;
	protected $name;
	protected $pointVersion;
	protected $compList;

    protected function setUp()
    {
        // Arrange
		$this->name = "Kalle";
		$this->pointVersion = "rank";
		$this->p1 = new Player($this->name,$this->pointVersion);
		$this->compList=array();
		$this->compList[]= new Competition(1,"Oban1",11,0,36,3,1,0); //  9+2=11p
		$this->compList[]= new Competition(2,"Oban2",6,0,34,1,0,1);  // 11+0=11p
		$this->compList[]= new Competition(3,"Oban3",11,0,36,5,1,0); //  7+2= 9p
		$this->compList[]= new Competition(4,"Oban4",6,0,34,6,0,1);  //  1+0= 1p
		$this->compList[]= new Competition(5,"Oban5",11,0,36,11,1,0);//  1+2= 3p
		$this->compList[]= new Competition(6,"Oban6",6,1,34,5,1,1);	 //  6+4=10p
		$this->p1->addCompetition($this->compList[0]);
		$this->p1->addCompetition($this->compList[1]);
		$this->p1->addCompetition($this->compList[2]);
		$this->p1->addCompetition($this->compList[3]);
		$this->p1->addCompetition($this->compList[4]);
		$this->p1->addCompetition($this->compList[5]);
	}

	/**
     * @covers   \obanTour\Player::getBestResult
     * @uses     \obanTour\Player::__construct
     */
	public function testGetBestPoints()
	{
		$this->assertEquals(41, $this->p1->getBestPoints(4));
		$this->assertEquals(22, $this->p1->getBestPoints(2));
		$this->assertEquals(45, $this->p1->getBestPoints(6));
	}

	/**
     * @covers   \obanTour\Player::getBestResult
     * @uses     \obanTour\Player::__construct
     */
	public function testGetBestPointsVersion2017()
	{
		$this->p1->setPointVersion("rank2017");
		$this->assertEquals(41+2, $this->p1->getBestPoints(4)); // 2p comp. bonus
		$this->assertEquals(22+4, $this->p1->getBestPoints(2)); // 4p comp. bonus
		$this->assertEquals(45+0, $this->p1->getBestPoints(6)); // 0p comp. bonus
	}

	public function testGetName()
	{
		$this->assertEquals($this->name, $this->p1->getName());
	}

	public function testGetPoints()
	{
		$i=0;
		foreach ($this->compList as $c) {
			$this->assertEquals($c->getTotalPoints(), $this->p1->getPoints($i++));
		}
	}

	public function testGetTableString()
	{
		//$this->assertEquals(count($this->compList) + 1, substr_count($this->p1->getTableString(4),"<td>"));
		$this->assertEquals(1, substr_count($this->p1->getTableString(4),"<td>")); // <td> for name
	}

	public function testGetNumberOfBottles()
	{
		$this->assertEquals(8,$this->p1->getNumberOfBottles());
	}
}
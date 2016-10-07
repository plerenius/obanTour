<?php
require_once 'PHPUnit\Autoload.php';

//use PHPUnit\Framework\TestCase;

class CompetitionTest extends PHPUnit_Framework_TestCase
{
	protected $c1;
	protected $id;
	protected $name;
	protected $numOfPlayers;
	protected $doublePointsFalse;
	protected $doublePointsTrue;
	protected $result;
	protected $rank;
	protected $closestFlag;
	protected $longestDrive;
	
    protected function setUp()
    {
        // Arrange
		$this->id = 9;
		$this->name = "Oban16-1";
		$this->numOfPlayers = 11;
		$this->result = 36;
		$this->rank = 2;
		$this->closestFlag = 1;
		$this->longestDrive = 1;
		$this->doublePointsFalse = 0;
		$this->doublePointsTrue = 1;
		$this->c1 = new Competition($this->id,$this->name,$this->numOfPlayers,
			$this->doublePointsFalse,$this->result,$this->rank,
			$this->closestFlag,$this->longestDrive);
		$this->c2 = new Competition($this->id,$this->name,$this->numOfPlayers,
			$this->doublePointsTrue,$this->result,$this->rank,
			$this->closestFlag,$this->longestDrive);
	}
	
	/**
     * @covers   \obanTour\Competition::getId
     * @uses     \obanTour\Competition::__construct
     */
	public function testGetId() {
		$this->assertEquals($this->id, $this->c1->getId());
	}
	
	public function testGetName() {
		$this->assertEquals($this->name, $this->c1->getName());
	}

	public function testGetRank() {
		$this->assertEquals($this->rank, $this->c1->getRank());
	}
	
	public function testGetClostestFlag() {
		$this->assertEquals($this->closestFlag, $this->c1->getClosestFlag());
	}
	
	public function testGetLongestDrive() {
		$this->assertEquals($this->longestDrive, $this->c1->getLongestDrive());
	}
	
	public function testGetRankPoints() {
		$this->assertEquals(10 * ($this->numOfPlayers - $this->rank) / ($this->numOfPlayers - 1) + 1,$this->c1->getRankPoints());
	}

	public function testGetDoubleRankPoints() {
		$this->assertEquals((10 * ($this->numOfPlayers - $this->rank) / ($this->numOfPlayers - 1) + 1)*2,$this->c2->getRankPoints());
	}
	
	public function testGetTotalPoints() {
		$this->assertEquals(10 * ($this->numOfPlayers - $this->rank) / ($this->numOfPlayers - 1) + 3,$this->c1->getTotalPoints());
	}
}
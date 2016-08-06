<?php
require_once 'PHPUnit\Autoload.php';

//use PHPUnit\Framework\TestCase;

class CompetitionTest extends PHPUnit_Framework_TestCase
{
	protected $c1;
	protected $id;
	protected $name;
	protected $numOfPlayers;
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
		$this->closestFlag = 0;
		$this->longestDrive = 1;
		$this->c1 = new Competition($this->id,$this->name,$this->numOfPlayers,$this->result,$this->rank,$this->closestFlag,$this->longestDrive);
	}
	
	/**
     * @covers   \obanTour\Player::getBestResult
     * @uses     \obanTour\Player::__construct
     */
	public function testGetId()
	{
		$this->assertEquals($this->id, $this->c1->getId());
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
	
	public function testGetPoints() {
		$this->assertEquals(10 * ($this->numOfPlayers - $this->rank) / ($this->numOfPlayers - 1) + 1,$this->c1->getPoints());
	}
}
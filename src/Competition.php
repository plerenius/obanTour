<?php

class Competition {
	protected $result;
	protected $rank;
	protected $closestFlag;
	protected $longestDrive;
	protected $numOfPlayers;
	
	function __construct($id, $name, $numOfPlayers, $result, $rank, $closestFlag, $longestDrive) {
		echo "Creating a competition named $name<br />";
		$this->id=$id;
		$this->name=$name;
		$this->numOfPlayers=$numOfPlayers;
		$this->result=$result;
		$this->rank=$rank;
		$this->closestFlag=$closestFlag;
		$this->longestDrive=$longestDrive;
	}
	
	function getId() {
		return $this->id;
	}
	
	function getRank() {
		return $this->rank;
	}
	
	function getClosestFlag() {
		return $this->closestFlag;
	}
	
	function getLongestDrive() {
		return $this->longestDrive;
	}
	
	function getRankPoints() {
		return (10 * ($this->numOfPlayers - $this->rank) / ($this->numOfPlayers - 1) + 1);
	}
	
	function getTotalPoints() {
		return $this->getRankPoints() + (($this->closestFlag != 0)?2:0);
	}
}
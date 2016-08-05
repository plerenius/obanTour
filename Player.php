<?php

class Player {
	private $name = "No name";
	private $results;
	
	function __construct($name,$results) {
		echo "Creating a player named $name<br />";
		$this->name=$name;
		$this->results=$results;
	}
	
	function getBestResult($numberOfResults) {
		$tot = 0;
		$sortedResult = $this->results;
		rsort($sortedResult);	
		for ($i=0;$i<$numberOfResults;$i++) {
			echo "Resultat $i: ".$sortedResult[$i]."<br />";
			$tot += $sortedResult[$i];
		}
		return $tot;
	}
	
	function getName() {
		return $this->name;
	}
	
	function getResult($index) {
		return $this->results[$index];
	}
}
?>
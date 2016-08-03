<?php

class player {
	public $name = "No name";
	public $results = array(0,0,0,0,0,0);
	public $total = 0;
	public $topFour = array(-1,-2,-3,-4);
	
	function __construct($name,$results) {
		echo "Creating a player named $name<br />";
		$this->name=$name;
		$this->results=$results;
		foreach ($this->results as $r){	
			for ($i=3;$i>0;$i--) {
				if ($r > $this->topFour[$i]) {
					$this->topFour[$i]= $this->topFour[$i];
				} else {
					break;
				}
			}
		}
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
}

$p1 = new player("Kalle",array(1,2,3,4,5,6));
echo "Result of round 1: " . $p1->results[0] . "<br />";
echo "Best 4 Result: " . $p1->getBestResult(4)
?>
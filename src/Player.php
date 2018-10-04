<?php

require_once("Competition.php");

class Player {
	private $name = "No name";
	private $pointVersion = 0;
	private $competitions = array();
	private $numberOfBottles = array(0,0,0);

	function __construct($name,$pointVersion) {
		$this->name=$name;
		$this->pointVersion = $pointVersion;
	}

	function addCompetition($comp) {
		$this->numberOfBottles[0] += ($comp->getRank() == 1)?1:0;
		$this->numberOfBottles[1] += ($comp->getLongestDrive() == 0)?0:1;
		$this->numberOfBottles[2] += ($comp->getClosestFlag() == 0)?0:1;
		$this->competitions[] = $comp;
	}

   function getTopResults($numberOfResults) {
        $results = array();
        foreach ($this->competitions as $c) {
            array_push($results, $c->getTotalPoints());
        }
        rsort($results);
        $topResults = array_slice($results,0,$numberOfResults);
        return $topResults;
    }
    
	function getBestPoints($numberOfResults) {
        $totalPoints = array_sum($this->getTopResults($numberOfResults));
        $totalPoints += $this->getBonusPoints($numberOfResults);
        return $totalPoints;
	}

    function getBonusPoints($numberOfResults) {
        $bonus = 0;
        // Bonus from Season 2017
		if ($this->pointVersion == "rank2017") {
            $noOfComp=array_reduce($this->competitions, function ($count, $comp) {
                return ($comp->getTotalPoints() > 0) ? ++$count : $count;
                });
			$bonus = max($noOfComp-$numberOfResults,0);
		}
        return $bonus;
    }

	function getName() {
		return $this->name;
	}

	function getPointVersion() {
		return $this->pointVersion;
	}

	function setPointVersion($pointVersion) {
		$this->pointVersion=$pointVersion;
	}

	function getPoints($index) {
		return $this->competitions[$index]->getTotalPoints();
	}

	function getTableString($numberOfResults) {
        $topResults = $this->getTopResults($numberOfResults);
		$str = "<td>".$this->name."</td>\n";
		foreach ($this->competitions as $c) {
            $bgcolor = (in_array($c->getTotalPoints(), $topResults)) ? "#bbffbb" : "#ffffff";
                    
			if ($c->getRankPoints() == 0) {
				$str .= "<td style='text-align:right;'>-</td>\n";
			} else {
                $str .= "<td style='";
                // Underline when Closest to Pin is won
                $str .= ($c->getClosestFlag() > 0 ? "text-decoration:underline;": "");
                $str .= "text-align:right;' bgcolor=\"$bgcolor\">";
                $str .= number_format($c->getTotalPoints(),2);
                $str .= "</td>\n";
			}
            $str .= "</td>\n";
		}
        if ($this->pointVersion == "rank2017") {
            $str .= "<td style='text-align:right;'>".$this->getBonusPoints($numberOfResults)."</td>\n";
        }
		$str .=  "<td style='text-align:right;'><b>".number_format($this->getBestPoints($numberOfResults),2)."</b></td>";
		return $str;
	}

	function getNumberOfBottles() {
		return $this->numberOfBottles[0] + $this->numberOfBottles[1] + $this->numberOfBottles[2];
	}

	function getBottleTableString() {
		if (true) { //$this->getNumberOfBottles() != 0) {
			$str  = "<td>".$this->name."</td>\n";
			$str .= "<td align=\"right\">".$this->numberOfBottles[0]."</td>\n";
			$str .= "<td align=\"right\">".$this->numberOfBottles[1]."</td>\n";
			$str .= "<td align=\"right\">".$this->numberOfBottles[2]."</td>\n";
			$str .=  "<td align=\"right\"><b>".$this->getNumberOfBottles()."</b></td>";
		}
		else {
			$str = "";
		}
		return $str;
	}
}
?>
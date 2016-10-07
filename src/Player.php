<?php

require_once("Competition.php");

class Player {
	private $name = "No name";
	private $competitions = array();
	private $numberOfBottles = array(0,0,0);
	
	function __construct($name) {
		$this->name=$name;
	}
	
	function addCompetition($comp) {
		$this->numberOfBottles[0] += ($comp->getRank() == 1)?1:0;
		$this->numberOfBottles[1] += ($comp->getLongestDrive() == 0)?0:1;	
		$this->numberOfBottles[2] += ($comp->getClosestFlag() == 0)?0:1;
		$this->competitions[] = $comp;
	}
	
	function getBestPoints($numberOfResults) {
		$tot = 0;
		$sortedResult = $this->competitions;
		usort($sortedResult,function($a,$b) {
			return $a->getTotalPoints() < $b->getTotalPoints()?1:-1;
		});
		for ($i=0;$i<$numberOfResults && $i<count($sortedResult);$i++) {
			$tot += $sortedResult[$i]->getTotalPoints();
		}
		return $tot;
	}
	
	function getName() {
		return $this->name;
	}
	
	function getPoints($index) {
		return $this->competitions[$index]->getTotalPoints();
	}
	
	function getTableString($numberOfResults) {
		$str = "<td>".$this->name."</td>\n";
		foreach ($this->competitions as $c) {
			if ($c->getRankPoints() == 0) {
				$str .= "<td align=right>-</td>\n";
			} else {
				$str .= "<td align=right>".number_format($c->getRankPoints(),2)."</td>\n";
			}
			if ($c->getClosestFlag() > 0) {
				if ($c->getDoublePoints() == 1) {
					$str .= "<td>+4</td>\n";
				} else {
					$str .= "<td>+2</td>\n";
				}
			} else {
				$str .= "<td>&nbsp;</td>\n";
			}
		}
		$str .=  "<td align=right><b>".number_format($this->getBestPoints($numberOfResults),2)."</b></td>";
		return $str;
	}
	
	function getNumberOfBottles() {
		return $this->numberOfBottles[0] + $this->numberOfBottles[1] + $this->numberOfBottles[2];
	}
	
	function getBottleTableString() {
		if (true) { //$this->getNumberOfBottles() != 0) {
			$str  = "<td>".$this->name."</td>\n";
			$str .= "<td align=right>".$this->numberOfBottles[0]."</td>\n";
			$str .= "<td align=right>".$this->numberOfBottles[1]."</td>\n";
			$str .= "<td align=right>".$this->numberOfBottles[2]."</td>\n";
			$str .=  "<td align=right><b>".$this->getNumberOfBottles()."</b></td>";
		}
		else {
			$str = "";
		}
		return $str;
	}
}
?>
<?php

class Player {
	private $name = "No name";
	private $competitions = array();
	
	function __construct($name) {
		echo "Creating a player named $name<br />";
		$this->name=$name;
	}
	
	function addCompetition($comp) {
		$this->competitions[] = $comp;
	}
	
	function getBestPoints($numberOfResults) {
		$tot = 0;
		$sortedResult = $this->competitions;
		usort($sortedResult,function($a,$b) {
			return $a < $b?1:-1;
		});
		print_r($sortedResult);
		for ($i=1;$i<=$numberOfResults;$i++) {
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
		$nf=0;
		$str = "<td>".$this->name."</td>\n";
		foreach ($this->competitions as $c) {
			if ($c->getRankPoints() == 0) {
				$str .= "<td align=right>-</td>\n";
			} else {
				$str .= "<td align=right>".number_format($c->getRankPoints(),2)."</td>\n";
			}
			if ($c->getClosestFlag() > 0) {
				$str .= "<td>+2</td>\n";
			} else {
				$str .= "<td>&nbsp;</td>\n";
			}
		}
		$str .=  "<td align=right><b>".number_format($this->getBestPoints($numberOfResults),2)."</b></td></tr>";
		return $str;
	}
}
?>
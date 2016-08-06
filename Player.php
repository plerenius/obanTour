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
	
	function getTableString($numberOfResults) {
		$nf=0;
		$str = "<td>".$this->name."</td>\n";
		foreach ($this->results as $r) {
			if ($r == 0) {
				$str .= "<td align=right>-</td>\n";
			} else {
				$str .= "<td align=right>".number_format($r,2)."</td>\n";
			}
			if ($nf > 0) {
				$str .= "<td>+2</td>\n";
			} else {
				$str .= "<td>&nbsp;</td>\n";
			}
		}
		$str .=  "<td align=right><b>".number_format($this->getBestResult($numberOfResults),2)."</b></td></tr>";
		return $str;
	}
}
?>
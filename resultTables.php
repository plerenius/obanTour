<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
require_once("Connections/pdo_connect.php");
require_once("Player.php");

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (ISSET($_GET['year'])) {
	$year=$_GET['year'];
} else {
	$year=2016;
}

echo "Year set to $year!<br />";

$competions_sql = "SELECT COUNT(r.id) AS numOfPlayers,c.* FROM competitions AS c\n"
	. "LEFT JOIN results AS r ON r.competitions_id = c.id\n"
    . "WHERE c.yearsId = $year  GROUP BY c.id ORDER BY c.date\n";
//echo "$competions_sql<br />";

$competions_q = $db->prepare($competions_sql);
$competions_q->execute();
$competitions = $competions_q->fetchAll();

$result_sql = "SELECT CONCAT(fname,\" \",lname) AS name,\n";
foreach ($competitions as $c) {
	$result_sql .= "SUM(IF(c.id=". $c['id'] .",10*(".$c['numOfPlayers']."-r.rank)/(".$c['numOfPlayers']."-1)+1,0)) AS c". $c['id'] .",\n";
	$result_sql .= "SUM(IF(p.id=". $c['nf'] .",2,0)) AS nf". $c['id'] .",\n";	
}


$result_sql .= "SUM(r.result+IF(p.id=c.nf,2,0)) AS total FROM competitions AS c\n"
    . "LEFT JOIN results AS r ON r.competitions_id = c.id\n"
	. "LEFT JOIN players AS p ON r.players_id = p.id\n"
    . "WHERE c.yearsId = $year GROUP BY p.id ORDER BY total DESC";
//echo "$result_sql";
$result_q = $db->prepare($result_sql);
$result_q->execute();

$players_r = $result_q->fetchAll();
//print_r($players_r[0]);

$playerList=array();
foreach ($players_r as $p) {
	$result=array();
	$nf=array();
	foreach ($competitions as $c) {
		$result[] = number_format($p["c".$c['id']],2);
		$nf[] = $p["nf".$c['id']];
	}
	$playerList[]=new Player($p['name'],$result);
}

$numOfCompetitions = 4;

function cmp($a, $b)
{
	$resultA = $a->getBestResult(4);
	$resultB = $b->getBestResult(4);
    return $resultA == $resultB ? 0 : ( $resultA > $resultB ) ? -1 : 1;
}

usort($playerList, "cmp");
?>
<table>
<tbody>
<tr>
<th>#</th>
<th>SPELARE</th>
<?php
$i=1;
foreach ($competitions as $c) {
	echo "<th style=\"text-align: right;\">#".$i++."</th><th>&nbsp;</th>";
}
?>
<th style="text-align: right;">TOTALT</th>
</tr>
<?php
$pos=0;
foreach ($playerList as $p) {
	$pos++;
	echo "<tr><td>".$pos."</td>\n";
	echo $p->getTableString($numOfCompetitions);
}
echo "<tr><th> Old list</th></tr>";
$pos = 0;
foreach ($players_r as $p) {
	$pos++;
	echo "<tr><td>".$pos."</td>\n";
	echo "<td>".$p['name']."</td>\n";
	foreach ($competitions as $c) {
		if ($p["c".$c['id']] == 0) {
			echo "<td align=right>-</td>\n";
		} else {
			echo "<td align=right>".number_format($p["c".$c['id']],2)."</td>\n";
		}
		if ($p["nf".$c['id']] > 0) {
			echo "<td>+2</td>\n";
		} else {
			echo "<td>&nbsp;</td>\n";
		}
	}
	echo "<td align=right><b>".number_format($p['total'],2)."</b></td></tr>";
}
echo "</table>";
?>

<?php
$s="select `c`.`yearsId` AS `year`,`p`.`fname` AS `fname`,`p`.`lname` AS `lname`,sum(((if((`r`.`rank` = 1),1,0) + if((`c`.`nf` = `p`.`id`),1,0)) + if((`c`.`ld` = `p`.`id`),1,0))) AS `vinpavor` from ((`imath_se`.`competitions` `c` left join `imath_se`.`results` `r` on((`c`.`id` = `r`.`competitions_id`))) join `imath_se`.`players` `p` on((`r`.`players_id` = `p`.`id`))) group by `c`.`yearsId`,`p`.`id` order by sum(((if((`r`.`rank` = 1),1,0) + if((`c`.`nf` = `p`.`id`),1,0)) + if((`c`.`ld` = `p`.`id`),1,0))) desc,`p`.`fname`";
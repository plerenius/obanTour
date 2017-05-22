<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<body>
<?php
require_once("Connections/pdo_connect.php");
require_once("src/Player.php");

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (ISSET($_GET['year'])) {
	$year=$_GET['year'];
} else {
	$year=2016;
}

$competions_sql = "SELECT COUNT(r.id) AS numOfPlayers,c.*,\n"
	. "CONCAT(p_win.fname,\" \",p_win.lname) AS winner,\n"
	. "CONCAT(p_nf.fname,\" \",p_nf.lname) AS nfName,\n"
	. "CONCAT(p_ld.fname,\" \",p_ld.lname) AS ldName\n"
	. "FROM competitions AS c\n"
	. "LEFT JOIN results AS r ON r.competitions_id = c.id\n"
	. "LEFT JOIN (SELECT * FROM results WHERE rank=1) AS r_win ON r_win.competitions_id = c.id\n"
	. "LEFT JOIN players AS p_win ON r_win.players_id = p_win.id\n"
	. "LEFT JOIN players AS p_nf ON c.nf = p_nf.id\n"
	. "LEFT JOIN players AS p_ld ON c.ld = p_ld.id\n"
    . "WHERE c.yearsId = $year  GROUP BY c.id ORDER BY c.date, r.rank\n";
//echo "$competions_sql<br />";

$competions_q = $db->prepare($competions_sql);
$competions_q->execute();
$competitions = $competions_q->fetchAll();

$result_sql = "SELECT\n";
foreach ($competitions as $c) {
	$result_sql .= "SUM(IF(c.id=". $c['id'] .",r.rank,0)) AS r". $c['id'] .",\n";
	$result_sql .= "SUM(IF(p.id=". ($c['ld']!=NULL?$c['ld']:-1) .",2,0)) AS ld". $c['id'] .",\n";
	$result_sql .= "SUM(IF(p.id=". ($c['nf']!=NULL?$c['nf']:-1) .",2,0)) AS nf". $c['id'] .",\n";
}


$result_sql .= "CONCAT(fname,\" \",lname) AS name FROM competitions AS c\n"
    . "LEFT JOIN results AS r ON r.competitions_id = c.id\n"
	. "LEFT JOIN players AS p ON r.players_id = p.id\n"
    . "WHERE c.yearsId = $year GROUP BY p.id ORDER BY rank";
//echo "$result_sql";
$result_q = $db->prepare($result_sql);
$result_q->execute();

$players_r = $result_q->fetchAll();
//print_r($players_r[0]);

$playerList=array();
foreach ($players_r as $p) {
	$playerList[]=new Player($p['name']);
	foreach ($competitions as $c) {
		$playerList[count($playerList)-1]->addCompetition(new Competition($c['id'],$c['name'],$c['numOfPlayers'],$c['doublePoints'],-1,$p["r".$c['id']],$p["nf".$c['id']],$p["ld".$c['id']]));
	}	
}

$numOfCompetitions = 4;

function cmpRank($a, $b)
{
	$resultA = $a->getBestPoints(4);
	$resultB = $b->getBestPoints(4);
    return $resultA == $resultB ? 0 : ( $resultA > $resultB ) ? -1 : 1;
}

function cmpBottles($a, $b)
{
	$bottlesA = $a->getNumberOfBottles();
	$bottlesB = $b->getNumberOfBottles();
    return $bottlesA == $bottlesB ? 0 : ( $bottlesA > $bottlesB ) ? -1 : 1;
}

usort($playerList, "cmpRank");
echo "<h2>Resultat Oban ".$year."</h2>";
?>
<table>
<tbody>
<tr>
<th>Del&auml;vling</th>
<th>Bana</th>
<th>Vinnare</th>
<th>L&auml;ngsta Drive</th>
<th>N&auml;rmast H&aring;l</th>
</tr>
<?php
foreach ($competitions as $c) {
	echo "<tr><td>".$c['name']."</td>\n";
	echo "<td>".$c['course']."</td>\n";	
	echo "<td>".(strcmp($c['winner'],"")?$c['winner']:"-")."</td>\n";	
	echo "<td>".(strcmp($c['ldName'],"")?$c['ldName']:"-")."</td>\n";
	echo "<td>".(strcmp($c['nfName'],"")?$c['nfName']:"-")."</td></tr>\n";
}
?>
</tbody>
</table>

<h2 align="center">Aktuell ställning The Oban Tour <?php echo $year; ?></h2>
Det är resultaten från de fyra bästa deltävlingarna som räknas med i totalen.
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
	echo "</tr>";
}
?>
</table>
</tbody>

<h3>Vinlista</h3>
<table>
<tbody>
<tr>
<th>#</th>
<th>SPELARE</th>
<th>Vinst</th>
<th>Ld</th>
<th>Nf</th>
<th>Totalt</th>
<?php
usort($playerList, "cmpBottles");
$pos=0;
foreach ($playerList as $p) {
	if ($p->getNumberOfBottles() == 0) {
		break;
	}
	$pos++;
	echo "<tr><td>".$pos."</td>\n";
	echo $p->getBottleTableString();
	echo "</tr>";
}
?>
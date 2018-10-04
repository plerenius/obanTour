<?php
require_once(realpath(dirname(__FILE__)."/../Connections/pdo_connect.php"));
require_once(realpath(dirname(__FILE__)."/../src/Player.php"));

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!ISSET($year)) {
	$year=date("Y");
}

//TODO: Find the correct values from a db quary
if ($year >= 2017) {
    $pointVersion = "rank2017";
} else {
    $pointVersion = "rank";
}
$numOfCompetitions = 4;

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
	$playerList[]=new Player($p['name'],$pointVersion);
	foreach ($competitions as $c) {
		$playerList[count($playerList)-1]->addCompetition(new Competition($c['id'],$c['name'],$c['numOfPlayers'],$c['doublePoints'],-1,$p["r".$c['id']],$p["nf".$c['id']],$p["ld".$c['id']]));
	}	
}


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

//***********************************
// Start printing the overview table
//***********************************
echo "<h2>Resultat Oban ".$year."</h2>\n";
echo "<table>\n";
echo "<tbody>\n";
echo "  <tr>\n";
echo "    <th>Del&auml;vling</th>\n";
echo "    <th>Bana</th>\n";
echo "    <th>Vinnare</th>\n";
echo "    <th>L&auml;ngsta Drive</th>\n";
echo "    <th>N&auml;rmast H&aring;l</th>\n";
echo "  </tr>\n";

foreach ($competitions as $c) {
	echo "  <tr>\n    <td>".$c['name']."</td>\n";
	echo "    <td>".$c['course']."</td>\n";	
	echo "    <td>".(strcmp($c['winner'],"")?$c['winner']:"-")."</td>\n";	
	echo "    <td>".(strcmp($c['ldName'],"")?$c['ldName']:"-")."</td>\n";
	echo "    <td>".(strcmp($c['nfName'],"")?$c['nfName']:"-")."</td>\n  </tr>\n";
}
echo "</tbody>\n";
echo "</table>\n";

//***********************************
// Print the result table
//***********************************
echo "<h2>Aktuell ställning The Oban Tour " . $year . "</h2>\n";
echo "<p>Det är resultaten från de ". $numOfCompetitions . " bästa deltävlingarna som räknas med i totalen.</p>\n";
echo "<table>\n";
echo "<tbody>\n";
echo "  <tr>\n";
echo "    <th>#</th>\n";
echo "    <th>SPELARE</th>\n";
$i=1;
foreach ($competitions as $c) {
	echo "    <th style='text-align:center;'>#".$i++."</th>\n";
}
//TODO: Fix this ugly dirty fix...
if ($year >= 2017) {
    echo "    <th>BONUS</th>\n";
}
echo "    <th style='text-align:right;'>TOTALT</th>\n";
echo "  </tr>\n";
// Print rows of players
$pos=0;
foreach ($playerList as $p) {
	$pos++;
	echo "  <tr>\n";
    echo "    <td style='text-align:right;'>".$pos."</td>\n";
	echo $p->getTableString($numOfCompetitions)."\n";
	echo "  </tr>\n";
}
echo "</tbody>\n";
echo "</table>\n";

//***********************************
//  Print the Wine list
//***********************************
usort($playerList, "cmpBottles");
$pos=0;
echo "<h3>Vinlista</h3>\n";
echo "<table>\n";
echo "<tbody>\n";
echo "  <tr>\n";
echo "    <th>#</th>\n";
echo "    <th>SPELARE</th>\n";
echo "    <th>Vinst</th>\n";
echo "    <th>Ld</th>\n";
echo "    <th>Nf</th>\n";
echo "    <th>Totalt</th>\n</tr>\n";
foreach ($playerList as $p) {
	if ($p->getNumberOfBottles() == 0) {
		break;
	}
	$pos++;
	echo "  <tr>\n";
    echo "    <td>".$pos."</td>\n";
	echo $p->getBottleTableString();
	echo "  </tr>\n";
}
echo "</tbody>\n</table>\n";
echo "<p>";
echo "</p>";
?>
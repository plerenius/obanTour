<?php
require_once(realpath(dirname(__FILE__)."/../Connections/pdo_connect.php"));

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql  = "SELECT CONCAT(fname,\" \",lname) AS name, SUM(rank) AS sRank, COUNT(rank) AS nofC, SUM(rank)/COUNT(rank) AS snitt FROM competitions AS c\n"
    . "JOIN results AS r ON r.competitions_id = c.id\n"
    . "JOIN players AS p ON r.players_id = p.id\n"
    . "GROUP BY p.id\n"
    . "HAVING nofC > 1\n"
    . "ORDER BY snitt,nofC,name";

$rank_q = $db->prepare($sql);
$rank_q->execute();

$rank_r = $rank_q->fetchAll();

echo "<h3>Ranking lista</h3>\n";
echo "<table>\n";
echo "<tbody>\n";
echo "  <tr>\n";
echo "    <th>#</th>\n";
echo "    <th>Namn</th>\n";
echo "    <th>Rankingsumma</th>\n";
echo "    <th>Antal t&auml;vlingar</th>\n";
echo "    <th>Snitt</th>\n";
echo "  </tr>\n";

$pos=1;
$old_pos=1;
foreach ($rank_r as $p) {
	if ($p['snitt'] == 0) {
		break;
	}
	if ($pos-2>=0 and $p['snitt'] == $rank_r[$pos-2]['snitt']) {
		echo "  <tr>\n    <td align='right'>T".$old_pos."</td>\n";
	} elseif ($pos<count($rank_r) and $p['snitt'] == $rank_r[$pos]['snitt']) {
		$old_pos=$pos;
		echo "  <tr>\n    <td align='right'>T".$old_pos."</td>\n";
	} else {
		echo "  <tr>\n    <td align='right'>".$pos."</td>\n";
		$old_pos=$pos+1;
	}
	$pos++;
	echo "    <td>".$p['name']."</td>\n";
	echo "    <td>".$p['sRank']."</td>\n";
	echo "    <td>".$p['nofC']."</td>\n";	
	echo "    <td align='right'>".$p['snitt']."</td>\n";
	echo "  </tr>";
}
echo "</tbody>\n";
echo "</table>\n";

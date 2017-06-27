<?php
require_once(realpath(dirname(__FILE__)."/../Connections/pdo_connect.php"));

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "SELECT\n"
    . "	CONCAT(fname,\" \",lname) AS name,\n"
    . "    SUM(IF(rank=1,1,0)+IF(nf=p.id,1,0)+IF((ld=p.id),1,0)) AS sWine,\n"
    . "    COUNT(rank) AS nofC,\n"
    . "    SUM(IF(rank=1,1,0)+IF(nf=p.id,1,0)+IF((ld=p.id),1,0))/COUNT(rank) AS snitt\n"
    . "FROM competitions AS c\n"
    . "JOIN results AS r\n"
    . "	ON r.competitions_id = c.id\n"
    . "JOIN players AS p\n"
    . "	ON r.players_id = p.id\n"
    . "GROUP BY p.id\n"
    . "HAVING nofC > 1 AND sWine > 0\n"
    . "ORDER BY snitt DESC,nofC DESC,name";

$wine_q = $db->prepare($sql);
$wine_q->execute();

$wine_r = $wine_q->fetchAll();

echo "<h3>Vinare per t&auml;vling</h3>\n";
echo "<table>\n";
echo "<tbody>\n";
echo "  <tr>\n";
echo "    <th>#</th>\n";
echo "    <th>Namn</th>\n";
echo "    <th>Vinare</th>\n";
echo "    <th>T&auml;vlingar</th>\n";
echo "    <th>Vin/T&auml;vling %</th>\n";
echo "  </tr>\n";

$pos=1;
$old_pos=1;
foreach ($wine_r as $p) {
	if ($p['snitt'] == 0) {
		break;
	}
	if ($pos-2>=0 and $p['snitt'] == $wine_r[$pos-2]['snitt']) {
		echo "  <tr>\n    <td align='right'>T".$old_pos."</td>\n";
	} elseif ($pos<count($wine_r) and $p['snitt'] == $wine_r[$pos]['snitt']) {
		$old_pos=$pos;
		echo "  <tr>\n    <td align='right'>T".$old_pos."</td>\n";
	} else {
		echo "  <tr>\n    <td align='right'>".$pos."</td>\n";
		$old_pos=$pos+1;
	}
	$pos++;
	echo "    <td>".$p['name']."</td>\n";
	echo "    <td align='right'>".$p['sWine']."</td>\n";
	echo "    <td align='right'>".$p['nofC']."</td>\n";	
	echo "    <td align='right'>".number_format($p['snitt']*100,2)."%</td>\n";
	echo "  </tr>";
}
echo "</tbody>\n";
echo "</table>\n";

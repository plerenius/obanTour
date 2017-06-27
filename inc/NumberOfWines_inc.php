<?php
require_once(realpath(dirname(__FILE__)."/../Connections/pdo_connect.php"));

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$years_sql = "SELECT * FROM season";

$years_q = $db->prepare($years_sql);
$years_q->execute();
$years = $years_q->fetchAll();

if(!isset($type)) {
  $type=$_GET["type"];
}
echo "<p>Typ: $type</p>\n";
if ($type=="win"){
	$wineStr="IF(rank=1,1,0)";
}
elseif ($type=="nf") {
	$wineStr="IF(nf=p.id,1,0)";
}
elseif ($type=="ld") {
	$wineStr="IF((ld=p.id),1,0)";
}
else {
	$wineStr="IF(rank=1,1,0)+IF(nf=p.id),1,0)+IF((ld=p.id),1,0)";
}

$bottles_sql = "SELECT CONCAT(fname,\" \",lname) AS name,\n";
foreach ($years as $y) {
	$bottles_sql .= "SUM(IF(c.yearsId=". $y['year'] .",(".$wineStr."),0)) AS p". $y['year'] .",\n";
}
$bottles_sql .= "sum(".$wineStr.") AS vinpavor\n"
	. "FROM competitions AS c\n"
    . "LEFT JOIN results AS r ON r.competitions_id = c.id\n"
	. "LEFT JOIN players AS p ON r.players_id = p.id\n"
    . "GROUP BY p.id ORDER BY vinpavor DESC, name";
//
echo "$bottles_sql";
$bottles_q = $db->prepare($bottles_sql);
$bottles_q->execute();

$bottles_r = $bottles_q->fetchAll();
//print_r($players_r[0]);

echo "<h3>Vinlista</h3>\n";
echo "<table>\n";
echo "<tbody>\n";
echo "  <tr>\n";
echo "    <th>#</th>\n";
echo "    <th>Namn</th>\n";
foreach ($years as $y) {
	echo "    <th>".$y['year']."</th>\n";
	$total[$y['year']]=0;
}
$total['tot']=0;


echo "    <th>Totalt</th>\n";
echo "  </tr>\n";

$pos=1;
$old_pos=1;
foreach ($bottles_r as $p) {
	if ($p['vinpavor'] == 0) {
		break;
	}
	if ($pos-2>=0 and $p['vinpavor'] == $bottles_r[$pos-2]['vinpavor']) {
		echo "  <tr>\n    <td align='right'>T".$old_pos."</td>\n";
	} elseif ($pos<count($bottles_r) and $p['vinpavor'] == $bottles_r[$pos]['vinpavor']) {
		$old_pos=$pos;
		echo "  <tr>\n    <td align='right'>T".$old_pos."</td>\n";
	} else {
		echo "  <tr>\n    <td align='right'>".$pos."</td>\n";
		$old_pos=$pos+1;
	}
	$pos++;
	echo "    <td>".$p['name']."</td>\n";
	foreach ($years as $y) {
		echo "    <td align='right'>".$p["p".$y['year']]."</td>\n";
		$total[$y['year']]+=$p["p".$y['year']];
	}
	echo "    <td align='right'>".$p['vinpavor']."</td>\n";
	$total['tot']+=$p['vinpavor'];
	echo "  </tr>";
}
echo "  <tr>\n    <td></td>\n    <td></td>\n";
foreach ($years as $y) {
	echo "    <td align='right'>".$total[$y['year']]."</td>\n";
}
echo "    <td align='right'>".$total['tot']."</td>\n";
echo "  </tr>\n";
echo "</tbody>\n";
echo "</table>\n";

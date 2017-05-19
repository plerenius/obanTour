<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
require_once("Connections/pdo_connect.php");

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$years_sql = "SELECT * FROM season";

$years_q = $db->prepare($years_sql);
$years_q->execute();
$years = $years_q->fetchAll();

$comps_sql = "SELECT CONCAT(fname,\" \",lname) AS name,\n";
foreach ($years as $y) {
	$comps_sql .= "SUM(IF(c.yearsId=". $y['year'] .",if(rank>0,1,0),0)) AS p". $y['year'] .",\n";
}
$comps_sql .= "sum(if((rank>0),1,0)) AS tot\n"
	. "FROM competitions AS c\n"
    . "LEFT JOIN results AS r ON r.competitions_id = c.id\n"
	. "LEFT JOIN players AS p ON r.players_id = p.id\n"
    . "GROUP BY p.id ORDER BY tot DESC, name";
//echo "$comps_sql";
$comps_q = $db->prepare($comps_sql);
$comps_q->execute();

$comps_r = $comps_q->fetchAll();
//print_r($players_r[0]);

?>
<h3>T&auml;vlingslista</h3>
<table>
<tbody>
<tr>
<th>#</th>
<th>Namn</th>
<?php
$total=array();
foreach ($years as $y) {
	echo "<th>".$y['year']."</th>\n";
	$total[$y['year']]=0;
}
$total['tot']=0;
?>
<th>Totalt</th>
</tr>
<?php
$pos=1;
$old_pos=1;
foreach ($comps_r as $p) {
	if ($pos-2>=0 and $p['tot'] == $comps_r[$pos-2]['tot']) {
		echo "<tr><td align='right'>T".$old_pos."</td>\n";
	} elseif ($pos<count($comps_r) and $p['tot'] == $comps_r[$pos]['tot']) {
		$old_pos=$pos;
		echo "<tr><td align='right'>T".$old_pos."</td>\n";
	} else {
		echo "<tr><td align='right'>".$pos."</td>\n";
		$old_pos=$pos+1;
	}
	$pos++;
	echo "<td>".$p['name']."</td>\n";
	foreach ($years as $y) {
		echo "<td align='right'>".$p["p".$y['year']]."</td>\n";
		$total[$y['year']]+=$p["p".$y['year']];
	}
	echo "<td align='right'>".$p['tot']."</td>\n";
	$total['tot']+=$p['tot'];
	echo "</tr>";
}
echo "<tr><td></td><td></td>";
foreach ($years as $y) {
	echo "<td align='right'>".$total[$y['year']]."</td>\n";
}
echo "<td align='right'>".$total['tot']."</td></tr>\n";
?>
</table>
</tbody>

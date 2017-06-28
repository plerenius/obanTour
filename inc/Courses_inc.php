<?php
require_once(realpath(dirname(__FILE__)."/../Connections/pdo_connect.php"));

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql  = "SELECT\n"
    . "	course,\n"
    . "    COUNT(*) AS rounds\n"
    . "FROM competitions AS c\n"
    . "GROUP BY course\n"
    . "HAVING course <> \"?\"\n"
    . "ORDER BY rounds DESC, course";

$courses_q = $db->prepare($sql);
$courses_q->execute();

$courses_r = $courses_q->fetchAll();

echo "<h3>Rundor per bana</h3>\n";
echo "<table>\n";
echo "<tbody>\n";
echo "  <tr>\n";
echo "    <th>#</th>\n";
echo "    <th>Bana</th>\n";
echo "    <th>Antal rundor</th>\n";
echo "  </tr>\n";

$pos=1;
$old_pos=1;
foreach ($courses_r as $p) {
	if ($p['rounds'] == 0) {
		break;
	}
	if ($pos-2>=0 and $p['rounds'] == $courses_r[$pos-2]['rounds']) {
		echo "  <tr>\n    <td align='right'>T".$old_pos."</td>\n";
	} elseif ($pos<count($courses_r) and $p['rounds'] == $courses_r[$pos]['rounds']) {
		$old_pos=$pos;
		echo "  <tr>\n    <td align='right'>T".$old_pos."</td>\n";
	} else {
		echo "  <tr>\n    <td align='right'>".$pos."</td>\n";
		$old_pos=$pos+1;
	}
	$pos++;
	echo "    <td>".$p['course']."</td>\n";
	echo "    <td align='right'>".$p['rounds']."</td>\n";
	echo "  </tr>";
}
echo "</tbody>\n";
echo "</table>\n";

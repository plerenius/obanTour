<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
require_once("Connections/pdo_connect.php");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$players_sql = "SELECT p.*, COUNT(r.id) AS numbOfComp FROM players AS p\n"
    . "LEFT JOIN results AS r ON r.players_id = p.id\n"
    . "GROUP BY p.id ORDER BY numbOfComp DESC, p.fname";
$players_q = $db->prepare($players_sql);
$players_q->execute();
$players_r = $players_q->fetchAll();

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;
  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = strtr($theValue,',','.');
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

// Handle form submition
if (isset($_POST['submit'])) {
	$competition_stmt = $db->prepare("INSERT INTO competitions (name,date,yearsId,course,type,weather,nf,ld,doublePoints) 
    VALUES (?,?,?,?,?,?,?,?,?)");
	$return_value=$competition_stmt->execute(array($_POST['comp_name'],$_POST['comp_date'],$_POST['comp_year'],$_POST['comp_course'],$_POST['comp_type'],NULL, $_POST['nf'],$_POST['ld'],$_POST['doublePoints']));
	print "procedure returned $return_value<br />\n";
	$comp_id = $db->lastInsertId();
	echo "T&auml;vling: " . $_POST['comp_name'] . " -> " . $comp_id . "<br />\n";

	$result_stmt = $db->prepare("INSERT INTO results (players_id,competitions_id,result,rank)
	VALUES (?,?,?,?)");
	for ($i=0;$i<$_POST['numbOfPlayers'];$i++) {
		if ($_POST["rank_$i"] != "") {
			$result_stmt->execute(array($_POST["id_$i"],$comp_id,$_POST["result_$i"],$_POST["rank_$i"]));
			echo "DB ID f&ouml;r ". $_POST["id_$i"] . ": " . $db->lastInsertId() . "<br />\n";
		}
	}
}
?>

<head>
<meta http-equiv="Content-Type" content="text/html" />
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<title>Mata in ny Oban T&auml;vling</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>

<h1>Ny t&auml;vling</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form1" id="form1">
T&auml;vlingsnamn: <input type="text" name="comp_name" value="2016-7" size="30" /><br />
Datum: <input type="text" name="comp_date" value="2016-10-08" size="10" /><br />
&Aring;r: <input type="text" name="comp_year" value="2016" size="4" /><br />
T&auml;vlingstyp: <input type="text" name="comp_type" value="" size="30" /><br />
Bana: <input type="text" name="comp_course" value="Linköpings Gk" size="30" /><br />
N&auml;rmst Flagg: <select name="nf">
<option value="NULL">Ingen</option>
<?php

foreach($players_r as $nt){
	echo "<option value=".$nt['id'].">".$nt['fname']." ".$nt['lname']."</option>";
}
?>
</select><br />
Longest Drive: <select name="ld">
<option value="NULL">Ingen</option>
<?php 
foreach($players_r as $nt){
	echo "<option value=".$nt['id'].">".$nt['fname']." ".$nt['lname']."</option>";
}
?>
</select><br />
Dubbla po&auml;ng: <select name="doublePoints">
<option value=1>Ja</option>
<option value=0>Nej</option>
</select><br />
<table>
<tr>
<th align=left>Namn</th>
<th align=left>#T&auml;vl</th>
<th align=left>Resultat</th>
<th align=left>Placering</th>
</tr>
<?php
$i=0;
foreach($players_r as $nt){
    echo "<tr>\n";
    echo "<td>".$nt['fname']." ".$nt['lname']."</td>\n";
	echo "<td>".$nt['numbOfComp']."</td>\n";
    echo "<td>\n"
		. "<input type=\"text\" name=\"result_$i\" value=\"\" size=\"5\" />\n"
		. "<input type=\"hidden\" name=\"id_$i\" value=\"".$nt['id']."\"/>\n"
		. "</td>\n";
    echo "<td>"
		. "<input type=\"text\" name=\"rank_$i\" value=\"\" size=\"5\" />"
		. "</td>\n";
    echo "</tr>\n";
	$i++;
}
echo "<input type=\"hidden\" name=\"numbOfPlayers\" value=\"".$i."\"/>\n";
echo "</table>\n";
?>
<input type="submit" name="submit" value="L&auml;gg till t&auml;vling" />
</form>
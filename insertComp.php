<?php
/**
 * Will implement page to insert new competition.
 * 
 *  PHP version 5
 * 
 * @category Golf
 * @package  PHP
 * @author   Petter Lerenius <plerenius@gmail.com>
 * @license  http://url.com license_name
 * @version  GIT: :git_id:
 * @link     github.com/plerenius/obenatour
 */

require_once "Connections/pdo_connect.php";
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['all'])) {
    $players_sql = "SELECT p.*, COUNT(r.id) AS numbOfComp FROM players AS p\n"
        . "LEFT JOIN results AS r ON r.players_id = p.id\n"
        . "GROUP BY p.id ORDER BY numbOfComp DESC, p.fname";
} else {
    $players_sql = "SELECT p.*, COUNT(r.id) AS numbOfComp FROM players AS p\n"
        . "  LEFT JOIN results AS r ON r.players_id = p.id\n"
        . "  LEFT JOIN competitions AS c ON r.competitions_id = c.id\n"
        . "  WHERE c.date > " . date("\"Y-m-d\"", strtotime("-3 year")) . "\n"
        . "  GROUP BY p.id\n"
        . "  ORDER BY numbOfComp DESC, p.fname";
} 
$players_q = $db->prepare($players_sql);
$players_q->execute();
$players_r = $players_q->fetchAll();

// Figure out competition number
$comp_id_q = $db->prepare("SELECT COUNT(id) AS comp_num FROM competitions WHERE YEAR(date) = YEAR(NOW())");
$comp_id_q->execute();
$comp_id = $comp_id_q->fetch(PDO::FETCH_ASSOC)['comp_num'] + 1;

/**
 * The getSQLValueString will escape and correct the value before inputed in db.
 * 
 * @param mixed  $theValue           The value to be escaped and corrected
 * @param string $theType            The type of the value
 * @param string $theDefinedValue    The value if type defined 
 * @param string $theNotDefinedValue - 
 * 
 * @return string $theValue Escaped and corrected value
 */
function getSQLValueString($theValue, $theType,
    $theDefinedValue = "", 
    $theNotDefinedValue = ""
) {
    $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;
    switch ($theType) {
    case "text":
        $theValue = ($theValue != "") ? $theValue : "NULL";
        break;
    case "long":
    case "int":
        $theValue = ($theValue != "") ? intval($theValue) : "NULL";
        break;
    case "double":
        $theValue = strtr($theValue, ',', '.');
        $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
        break;
    case "date":
        $theValue = ($theValue != "") ? $theValue : "NULL";
        break;
    case "defined":
        $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
        break;
    }
    return $theValue;
}

// Handle form submition
if (isset($_POST['submit'])) {
    $competition_stmt = $db->prepare(
        "INSERT INTO competitions
        (name,date,yearsId,course,type,weather,nf,ld,doublePoints) 
        VALUES (?,?,?,?,?,?,?,?,?)"
    );
    $return_value = $competition_stmt->execute(
        array(
            getSQLValueString($_POST['comp_name'], "text"),
            $_POST['comp_date'],
            getSQLValueString($_POST['comp_year'], "int"),
            getSQLValueString($_POST['comp_course'], "text"),
            getSQLValueString($_POST['comp_type'], "text"),
            null,
            getSQLValueString($_POST['nf'], "int"),
            getSQLValueString($_POST['ld'], "int"),
            getSQLValueString($_POST['doublePoints'], "double")
        )
    );
    $comp_id = $db->lastInsertId();

    $result_stmt = $db->prepare(
        "INSERT INTO results (players_id,competitions_id,result,rank)
        VALUES (?,?,?,?)"
    );
    for ($i = 0; $i < $_POST['numbOfPlayers']; $i++) {
        if ($_POST["rank_$i"] != "") {
            $result_stmt->execute(
                array(
                    $_POST["id_$i"],
                    $comp_id, 
                    getSQLValueString($_POST["result_$i"], "double"),
                    getSQLValueString($_POST["rank_$i"], "double")
                )
            );
        }
    }
    header("Location: ./seasonResult.php");
    die();
}
?>
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"
>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html" />
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Mata in ny Oban T&auml;vling</title>
  <link rel="stylesheet" 
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity=
      "sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" 
    crossorigin="anonymous"
  >
  <link rel="stylesheet"
    href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
    integrity=
      "sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf"
    crossorigin="anonymous"
  >
  <link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
  <nav class="navbar bg-dark navbar-dark">
    <div class="container">
      <a href="" class="navbar-brand">
        <i class="fas fa-golf-ball mr-3"></i>
        Oban Tour
      </a>
    </div>
  </nav>
  <section id="comp" class="container-fluid px-0">
    <div class="row justify-content-center">
      <div class="col-8">
        <h1>
          Ny t&auml;vling: <?php echo date("Y-$comp_id"); ?>
          <a class="btn btn-outline-info float-right" href="./insertComp.php?all=true">
            Visa alla spelare
          </a>
        </h1>
        <p></p>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>"
          method="post" name="form1" id="form1"
        >
          <input type="hidden" name="comp_year"
            value=<?php echo date("\"Y\""); ?> size="4"
          />
          <input type="hidden" name="comp_name"
            value=<?php echo date("\"Y-$comp_id\""); ?>"
          />
          
          <table>
            <tr>
              <td>Datum:</td>
              <td>
                <input class="form-control" type="text" name="comp_date"
                  value=<?php echo date("\"Y-m-d\""); ?> size="10"
                />
              </td>
            </tr>
            <tr>
              <td>T&auml;vlingstyp:</td>
              <td>
                <input class="form-control" type="text" name="comp_type"
                  value="Poängbogey med inl&aring;sning" size="30"
                />
              </td>
            </tr>
            <tr>
              <td>Bana:</td>
              <td>
                <input class="form-control" type="text" name="comp_course"
                  value="-" size="30"
                />
              </td>
            </tr>
            <tr>
              <td>N&auml;rmst Flagg:</td>
              <td>
                <select class="form-control" name="nf">
                  <option value="NULL">Ingen</option>
                    <?php

                    foreach ($players_r as $nt) {
                        echo "<option value=" . $nt['id'] . ">" .
                            $nt['fname'] . " " . $nt['lname'] . "</option>";
                    }
                    ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>Longest Drive:</td>
              <td>
                <select class="form-control" name="ld">
                  <option value="NULL">Ingen</option>
                    <?php
                    foreach ($players_r as $nt) {
                        echo "<option value=" . $nt['id'] . ">" .
                            $nt['fname'] . " " . $nt['lname'] . "</option>";
                    }
                    ?>
                </select>
              </td>
            </tr>
            
            <input type="hidden" name="doublePoints"
            value=<?php echo ($comp_id == 8) ? 1.5 : 1; ?>"
            />
          </table>
          <p>
             Mata in resultatet från tävlingen. Resultat kolumnen innehåller ett decimaltal
             som motsvarar resultatet för spelaren, t.ex. poäng, antal slag.<br />
             Placering är spelarens placering i tävlingen. Vid delad plats matar man in
             +0.5, alltså delad andraplats ger 2.5 för de båda spelarna.
          </p>
          <p> 
            <table class="table table-sm table-striped">
              <tr>
                <th align=left class="pr-3">Namn</th>
                <th align=left>Resultat</th>
                <th align=left>Placering</th>
              </tr>
                <?php
                $i = 0;
                foreach ($players_r as $nt) {
                    echo "<tr>\n";
                    echo "<td>" . $nt['fname'] . " " . $nt['lname'] . "</td>\n";
                    echo "<td>\n"
                        . "<input type=\"text\" class=\"form-control\" "
                        . "name=\"result_$i\" value=\"\" size=\"5\"/>\n"
                        . "<input type=\"hidden\" name=\"id_$i\" value=\""
                        . $nt['id'] . "\"/>\n"
                        . "</td>\n";
                    echo "<td>"
                        . "<input type=\"text\" class=\"form-control\" "
                        . "name=\"rank_$i\" value=\"\" size=\"5\"/>"
                        . "</td>\n";
                    echo "</tr>\n";
                    $i++;
                }
                echo "<input type=\"hidden\" name=\"numbOfPlayers\"";
                echo "value=\"" . $i . "\"/>\n";
                echo "</table>\n";
                ?>
              <input
                class="btn btn-outline-success"
                type="submit"
                name="submit"
                value="L&auml;gg till t&auml;vling"
              />
            </table>
          </p>
        </form>
      </div>
    </div>
  </section>
</body>

</html>
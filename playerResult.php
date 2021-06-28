<?php
/**
 * This file prints the result of a specific player
 * 
 * PHP version 7
 *
 * @category Golf
 * @package  Oban
 * @author   Petter <petter@lerenius.se>
 * @license  xxx http://
 * @version  GIT: :git_id:
 * @link     http://obantour.lerenius.se
 */

if (ISSET($_GET['id'])) {
  $id = $_GET['id'];
} else {
  $id=2;
}

require realpath(dirname(__FILE__)."/Connections/pdo_connect.php");
//SELECT COUNT(*) AS numComp, MAX(c.date) AS latestComp, YEAR(MIN(c.date)) AS rookieYear FROM `results` AS r LEFT JOIN competitions AS c ON c.id=r.competitions_id WHERE players_id=42
$competions_sql = "SELECT * \n"
. "FROM results AS r \n"
. "LEFT JOIN competitions AS c ON c.id=r.competitions_id \n"
. "WHERE players_id = $id\n"
. "ORDER BY c.date \n";
echo "$competions_sql <br />";
$competions_q = $db->prepare($competions_sql);
$competions_q->execute();
$competitions = $competions_q->fetchAll();


// Figure out name
$name_q = $db->prepare("SELECT CONCAT(fname,\" \",lname) AS name FROM players WHERE id=$id");
$name_q->execute();
$name = $name_q->fetch(PDO::FETCH_ASSOC)['name'];

// Wins
$wins_sql = "SELECT COUNT(r.id) AS numOfPlayers,c.name, c.course\n"
. "FROM competitions AS c\n"
. "LEFT JOIN results AS r ON r.competitions_id = c.id\n"
. "LEFT JOIN (SELECT * FROM results WHERE rank=1) AS r_win "
. "ON r_win.competitions_id = c.id\n"
. "WHERE r_win.players_id = $id\n"
. "GROUP BY c.id ORDER BY c.date DESC, r.rank\n";
// echo $wins_sql;
$wins_q = $db->prepare($wins_sql);
$wins_q->execute();
$wins = $wins_q->fetchAll();
//print_r($wins);

//NF
$nf_sql = "SELECT COUNT(r.id) AS numOfPlayers,c.name, c.course\n"
. "FROM competitions AS c\n"
. "LEFT JOIN results AS r ON r.competitions_id = c.id\n"
. "WHERE c.nf = $id  GROUP BY c.id ORDER BY c.date DESC\n";
//echo "$nf_sql<br />";
$nf_q = $db->prepare($nf_sql);
$nf_q->execute();
$nfs = $nf_q->fetchAll();

//LD
$ld_sql = "SELECT COUNT(r.id) AS numOfPlayers,c.name, c.course\n"
. "FROM competitions AS c\n"
. "LEFT JOIN results AS r ON r.competitions_id = c.id\n"
. "WHERE c.ld = $id  GROUP BY c.id ORDER BY c.date DESC\n";
//echo "$ld_sql<br />";
$ld_q = $db->prepare($ld_sql);
$ld_q->execute();
$lds = $ld_q->fetchAll();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Player Result</title>
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
    <link rel="icon" href="golf.ico">
  </head>
  <body>
    <nav class="navbar bg-dark navbar-dark">
      <div class="container">
        <a href="" class="navbar-brand">
          <i class="fas fa-golf-ball mr-3"></i>Oban Tour
        </a>
      </div>
    </nav>
    <section id="result" class="container-fluid px-0">
      <div class="row justify-content-center">
        <div class="col-8">
          <h2><?php echo $name; ?></h2>
          <p>
            <?php
              echo count($competitions) . " Tävlingar</br>";
              echo count($wins) ." Vinster</br>";
              echo count($nfs) . " Närmst hål</br>";
              echo count($lds) . " Längst drive</br>";
              echo "Första tävlingen: "
                . $competitions[0]['name'] . " på "
                . $competitions[0]['course'] . ", " 
                . $competitions[0]['result'] . "p gav plats "
                . $competitions[0]['rank'] . "</br>";
              $c_end = end($competitions);
              echo "Senaste tävlingen: "
                . $c_end['name'] . " på "
                . $c_end['course'] . ", " 
                . $c_end['result'] . "p gav plats "
                . $c_end['rank'] . "</br>";
          ?>
          </p>
          
          <h4>Tävlingsvinster</h4>
          <p>
            <table class="table table-striped">
              <tr><th scope="col">Tävling</th><th scope="col">Bana</th></tr>
              <?php
                foreach ($wins as $win) {
                  echo "<tr><td>" . $win['name'] . "</td><td>" .$win['course'] . "</td></tr>";
                } 
              ?>
            </table>
          </p>

          <h4>Närmst hål</h4>
          <p>
            <table class="table table-striped">
              <tr><th scope="col">Tävling</th><th scope="col">Bana</th></tr>
              <?php
                foreach ($nfs as $nf) {
                  echo "<tr><td>" . $nf['name'] . "</td><td>" .$nf['course'] . "</td></tr>";
                } 
              ?>
            </table>
          </p>
          <h4>Längst drive</h4>
          <p>
            <table class="table table-striped">
              <tr><th scope="col">Tävling</th><th scope="col">Bana</th></tr>
              <?php
                foreach ($lds as $ld) {
                  echo "<tr><td>" . $ld['name'] . "</td><td>" .$ld['course'] . "</td></tr>";
                } 
              ?>
            </table>
          </p>
        </div>
      </div>
    </section>
  </body>
</html>

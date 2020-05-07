<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Result</title>
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
          <i class="fas fa-golf-ball mr-3"></i>Oban Tour
        </a>
      </div>
    </nav>
    <section id="result" class="container-fluid px-0">
      <div class="row justify-content-center">
        <div class="col-8">
          <?php
              require "inc/SeasonResult_inc.php";
              printTables();
          ?>
        </div>
      </div>
    </section>
  </body>
</html>

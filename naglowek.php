<?php

  session_start();

  if(isset($_SESSION['SESS_CHANGEID']) == TRUE)
  {
    session_unset();
    session_regenerate_id();
  }

  require("konfiguracja.php");
  $db = mysql_connect($dbhost, $dbuser, $dbpassword);
  mysql_query("SET NAMES latin2");
  mysql_select_db($dbdatabase, $db);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3c.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3c.org/1999/xhtml" xml:lang="pl" lang="pl">
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title><?php echo $config_sitename; ?></title>
  <link href="arkusz_stylow.css" rel="stylesheet">
</head>
<body>
  <div id="header">
  <h1><?php echo $config_sitename; ?></h1>
  </div>
  <div id="menu">
    <a href="<?php echo $config_basedir; ?>">Główna strona</a> -
    <a href="<?echo $config_basedir; ?>wyswietlanie_koszyka.php">Wyświetlanie koszyka/Płacenie</a>
  </div>
  <div id="container">
     <div id="bar">
       <?php

         require_once('pasek.php');
         echo "<hr>";

         if(isset($_SESSION["SESS_LOGGEDIN"]))
         {
           echo "Zalogowany jako <strong>" . $_SESSION['SESS_USERNAME'] . "</strong> [<a href='" . $config_basedir . "'wylogowywanie.php'>wyloguj</a>]";
         }
         else
         {
           echo '<a href="' . $config_basedir .'logowanie.php">Logowanie</a>';
         }
       ?>
     </div>
     <div id="main">

         
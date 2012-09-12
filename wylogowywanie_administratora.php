<?php
  session_satrt();

  require("konfiguracja.php");

  session_unregister("SESS_ADMINLOGGEDIN");

  header("Location: " . $config_basedir);
?>
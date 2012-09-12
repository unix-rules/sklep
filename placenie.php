<?php
  session_start();
  require("bd.php");
  require("funkcje.php");

  if($_POST['paypalsubmit'])
  {
    $upsql = "UPDATE zamowienia SET status = 2, metoda_platnosci = 1 WHERE id = " . $_SESSION['SESS_ORDERNUM'];
    $upres = mysql_query($upsql);
    $itemssql = "SELECT suma FROM zamowienia WHERE id =" . $_SESSION['SESS_ORDERNUM'];
    $itemsres = mysql_query($itemssql);
    $row = mysql_fetch_assoc($itemsres);

    if(($_SESSION['SESS_LOGGEDIN'])}
      unset($_SESSION['ORDERNUM']);
    }
    else
    {
      session_register("SESS_CHANGEID");
      $_SESSION['SESS_CHANGEID'] = 1;
    }
    header("Location: " . $config_basedir);
    /*header("Location: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=firma%40twoj_adres.com&item_name=" 
            . urlencode($config_sitename) . "+Order&item_number=PROD" . $row[id] ."&amount=" 
            . urlencode(sprintf('%.2f', $row[suma])) 
            . "&no_note=1&currency_code=GBP&lc=GB&submit.x=41&submit.y=15");*/
  }
  else if($_POST['chequesubmit'])
  {
    $upsql = "UPDATE zamowienia SET status = 2, metoda_platnosci = 2 WHERE id = " . $_SESSION['SESS_ORDERNUM'];
    $upres = mysql_query($upsql);

    if($_SESSION['SESS_LOGGEDIN'])
    {
      unset($_SESSION['SESS_ORDERNUM']);
    }
    else
    {
      session_register("SESS_CHANGEID");
      $_SESSION['SESS_CHANGEID'] = 1;
    }

    require("naglowek.php");
?>
    <h1>Płatność czekiem</h1>
    <u>Czek zostanie wystawiony dla</u> <strong><?php echo $config_sitename; ?></strong>
    <p>
    Czek zostanie wysłany na adres:
    <p>
    <?php echo $config_sitename; ?><br>
    22, Miejsce,<br>
    Miasto,<br>
    Kraj,<br>
    FG43 F3D.<br>
<?php
  }
  else
  {
    require("naglowek.php");
    echo "<h1>Płacenie</h1>";
    showcart();
?>
    <h2>Wybieramy metody platności</h2>
    <form action='placenie.php' method='POST'>
    <table cellspacing='10'>
    <tr>
      <td><h3>PayPal</h3></td>
      <td>Witryna Looproducts.com używa metody PayPal w celu akceptowaniakart Switch/Visa/Mastercard. Nie jest wymagane żadne konto metody PayPal  - podaje się jedynie szczegóły dotyczące karty, po czym rachunek zostanie obciążony odpowiednią kwotą.</td>
      <td>
        <input type='submit' name="paypalsubmit" value="Zapłać przy użyciu metody PayPal"></input>
      </td>
    </tr>
    <tr>
      <td><h3>Czek</h3></td>
      <td>Jeśli zamierza się zapłacić czekiem, po wystawieniu go na całkowitą kwotę należy wysłać go do siedziby witrynyLooproducts.com.</td>
      <td>
        <input type='submit' name="chequesubmit" value="Zapłać czekiem"></input>
      </td>
    </tr>
    </table>
    </form>
<?php
  }

  require("stopka.php");
?> 
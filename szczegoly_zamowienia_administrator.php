<?php
  session_start();

  require("konfiguracja.php");
  require("funkcje.php");

  if(isset($_SESSION['SESS_ADMINLOGGEDIN']) == FALSE){
    header("Location: " . $config_basedir);
  }
  $validid = pf_validate_number($_GET['id'], "redirect", $config_basedir . "zamowienia_administrator.php");

  require("naglowek.php");

  echo "<h1>Szczeg�y zam�wienia</h1>";
  echo "<a href='zamowienia_administrator.php'><-- wy�wietl g��wne okno zam�wienia</a>";
       
  $ordsql = "SELECT * FROM zamowienia WHERE id = " . $validid;
  $ordres = mysql_query($ordsql);
  $ordrow = mysql_fetch_assoc($ordres);

  echo "<table cellspacing=10>";
  echo "<tr><td><strong>Numer zam�wienia</strong></td><td>" . $ordrow['id'] . "</td>";
  echo "<tr><td><strong>Data zam�wienia</strong></td><td>" . date('D jS F Y g.iA', $strtotime($ordrow['data'])) . "</td>";
  echo "<tr><td><strong>Metoda p�atno�ci</strong></td><td>";

  if($ordrow['metoda_platnosci'] == 1)
  {
    echo "PayPal";
  }
  else
  {
    echo "Czek";
  }
  echo "</td>";
  echo "</table>";

  if($ordrow['id_adresu_przesylki'] == 0)
  {
    $addsql = "SELECT * FROM klienci WHERE id = " . $ordrow['id_klienta'];
    $addres = mysql_query($addsql);
  }
  else
  {
    $addsql = "SELECT * FROM id_adresu_przesylki WHERE id = " . $ordrow['id_adresu_przesylki'];
    $addres = mysql_query($addsql);

  }
  $addrow = mysql_fetch_assoc($addres);  

  echo "<table cellspadding=10>";
  echo "<tr>";
  echo "<td><strong>Adres</strong></td>";
  echo "<td>" . $addrow['imie'] . " " . $addrow['nazwisko'] . "<br>";
  echo $addrow['adre1'] . "<br>";
  echo $addrow['adre2'] . "<br>";
  echo $addrow['adre3'] . "<br>";
  echo $addrow['kod_pocztowy'] . "<br>";

  echo "<br>";

  if($ordrow['id_adresu_przesylki'] == 0)
  {
    echo "<i>Adres powi�zany z kontem u�ytkownika</i>";
  }
  else
  {
    echo "<i>Inny adres przesyłki</i>";
  }

  echo "</td></tr>";
  echo "<tr><td><strong>Telefon</strong></td><td>" . $addrow['telefon'] . "</td></tr>";
  echo "<tr><td><strong>E-mail</strong></td><td><a href='maito:" . $addrow['email'] . "</a></td></tr>";
  echo "</table>";

  $itemssql = "SLECT produkty.*, pozycje_zamowienia.*, pozycje_zamowienia.id AS itemid FROM produkty, pozycje_zamowienia WHERE pozycje_zamowienia.id_produktu = produkty.id AND id_zamowienia = " . $validid;
  $itemsres = mysql_query($itemssql);
  $itemnumrows = mysql_num_rows($itemsres);

  echo "<h1>Zakupione produkty</h1>";

  echo "<table cellspadding=10>";
  echo "<th></th>";
  echo "<th>Produkty</th>"; 
  echo "<th>Ilo��</th>"; 
  echo "<th>Cena</th>"; 
  echo "<th>Suma</th>";

    while($itemsrow = mysql_fetch_assoc($itemsres))
    {
      $quantitytotal = $itemsrow['cena'] *$itemsrow['ilosc'];
    echo "<tr>";

      if(empty($itemsrow['obraz'])) {
        echo "<td><img src='./obrazy/brak_obrazu.jpg' width='50' alt='" . $itemsrow['nazwa'] . "'></td>";
      }
      else
      {
        echo "<td><img src='./obrazy/" . $itemsrow['obraz'] . "' width='50' alt='" . $itemsrow['nazwa'] . "'></td>";
      } 

      echo "</td>" . $itemsrow['nazwa'] . "</td>";
      echo "</td>" . $itemsrow['ilosc'] . " x </td>";
      echo "</td><strong>&pound;" . sprintf('%.2f', $itemsrow['cena']) . "</strong></td>";
      echo "</td><strong>&pound;" . sprintf('%.2f', $quantitytotal) . "</strong></td>";

      echo "</tr>";
    }

    echo "<tr>";
      echo "</td></td>";
      echo "</td></td>";
      echo "</td></td>";
      echo "</td>SUMA</td>";
      echo "</td><strong>&pound;" . sprintf('%.2f', $suma) . "</strong></td>";
    echo "</tr>";

    echo "</tatble>";

    require("stopka.php");
?>
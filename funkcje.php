<?php
require_once("konfiguracja.php");
function pf_validate_number($value, $function, $redirect){
	
	$error = 0;
	$finall = 0;
	
	if(isset($value) == TRUE){
   

	
		if(is_numeric($value) == FALSE) {
		  $error = 1;
		}

		if($error == 1) {
		  header("Location: " . $redirect);
		}
		else {
		  $final = $value;
		}
	}
	else {
		if($function == 'redirect') {
		  header("Location:" . $redirect);
		}
    
		if($function == 'value') {
		  $final = 0;
		}
	}
	return $final;
}


function showcart()
{
  if(isset($_SESSION['SESS_ORDERNUM']) && $_SESSION['SESS_ORDERNUM'])
  {
    if(isset($_SESSION['SESS_LOGGEDIN']) && $_SESSION['SESS_LOGGEDIN'])
    {
      $custsql = 'SELECT id, status FROM zamowienia WHERE id_klienta = ' . $_SESSION['SESS_USERID'] . " AND status < 2;";
      $custres = mysql_query($custsql);
      $custrow = mysql_fetch_assoc($custres);

      $itemssql = 'SELECT produkty.*, pozycje_zamowienia.*, pozycje_zamowienia.id AS itemid FROM produkty, pozycje_zamowienia WHERE pozycje_zamowienia.id_produktu = produkty.id AND pozycje_zamowienia.id_klienta = ' . $custrow['id'];
	  $itemsres = mysql_query($itemssql);
      $itemnumrows = mysql_num_rows($itemsres);
    }
    else
    {
      $custsql = 'SELECT id, status FROM zamowienia WHERE sesja= ' . session_id() . "' AND status < 2;";
      $custres = mysql_query($custsql);
      $custrow = mysql_fetch_assoc($custres);

      $itemssql = "SELECT produkty.*, pozycje_zamowienia.*, pozycje_zamowienia.id AS itemid FROM produkty, pozycje_zamowienia WHERE pozycje_zamowienia.id_produktu = produkty.id AND pozycje_zamowienia.id_klienta = " . $custrow[id];
      $itemsres = mysql_query($itemssql);
      $itemnumrows = mysql_num_row($itemsres);
    }
  }
  else
  {
    $itemnumrows = 0;
  }

  if($itemnumrows == 0)
  {
    echo "Nie dodano jeszcze niczego do koszyka.";
  }
  else
  {
    echo "<table cellpadding=10>";
    echo "<tr>";
      echo "<td></td>";
      echo "<td><strong>Pozycja</strong></td>";
      echo "<td><strong>Ilość</strong></td>";
      echo "<td><strong>Cena jednostka</strong></td>";
      echo "<td><strong>Cena całkowita</strong></td>";
      echo "<td></td>";
    echo "</tr>";

	$suma = 0;
    while($itemsrow = mysql_fetch_assoc($itemsres))
    {
		//var_dump($itemsrow);echo "<br/ >";
		$quantitytotal = $itemsrow['cena'] * $itemsrow['ilosc'];
		echo "<tr>";

		  if(empty($itemsrow['obraz'])) {
			echo '<td><img src="' . CONFIG_BASEDIR .'/obrazy/brak_obrazu.jpg" width="50" alt="' . $itemsrow['nazwa'] . '"/></td>';
		  }
		  else {
			echo '<td><img src="' . CONFIG_BASEDIR .'/obrazy/' . $itemsrow['obraz'] . '" width="50" alt="' . $itemsrow['nazwa'] . '"/></td>';
		  }
		  
		  echo "<td>" . $itemsrow['nazwa'] . "</td>";
		  echo "<td>" . $itemsrow['ilosc'] . "</td>";
		  echo "<td><strong>&pound;" . sprintf('%.2f', $itemsrow['cena']) . "</strong></td>";
		  echo "<td><strong>&pound;" . sprintf('%.2f', $quantitytotal) . "</strong></td>";
		  echo "<td>[<a href='" . CONFIG_BASEDIR . "usuwanie.php?id=" . $itemsrow['itemid'] . "'>X</a>]</td>";
		  echo "</tr>";

		$suma = $suma + $quantitytotal;
		$totalsql = "UPDATE zamowienia SET suma = " . $suma . " WHERE id = " . $_SESSION['SESS_ORDERNUM'];
		$totalres = mysql_query($totalsql);
    }
	
    echo "<tr>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td>SUMA</td>";
    echo "<td><strong>&pound;" . sprintf('%.2f', $suma) . "</strong></td>";
    echo "<td></td>";
    echo "</tr>";

    echo "</table>";

    echo "<p><a href='placenie_adres.php'>Do kasy</a></p>";
    }
  }
?>
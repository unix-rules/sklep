<?php
  session_start();
  require("bd.php");

  $statussql = "SELECT status FROM zamowienia WHERE id  = " . $_SESSION['SESS_ORDERNUM'];
  $statusres = mysql_query($statussql);
  $statusrow = mysql_fetch_assoc($statusres);
  $status = $statusrow['status'];

  if($status == 1) {
    header("Location: " . $config_basedir . "placenie.php");
  }


  if($status >= 2) {
    header("Location: " . $config_basedir);
  }

  if($_POST['submit'])
  {
    if($_SESSION['LOGGEDIN'])
    {
      if($_POST['addselecBox'] == 2)
      {
        if(empty($_POST['forenameBox']) ||
          empty($_POST['surenameBox']) ||
          empty($_POST['add1Box']) ||
          empty($_POST['add2Box']) ||
          empty($_POST['add3Box']) ||
          empty($_POST['postcodeBox']) ||
          empty($_POST['phoneBox']) ||
          empty($_POST['emailBox']))
         {
           header("Location: " . $config_basedir . "placenie_adres.php?error=1");
           exit;
         }
         $addsql = "INSERT INTO id_adresu_przesylki(imie, nazwisko, adres1, adres2, adres3, kod_pocztowy, telefon, email)
                    VALUES('"
                    . strip_tags(addslashes($_POST['forenameBox'])) . "', '"
                    . strip_tags(addslashes($_POST['surenameBox'])) . "', '"
                    . strip_tags(addslashes($_POST['add1Box'])) . "', '"
                    . strip_tags(addslashes($_POST['add2Box'])) . "', '"
                    . strip_tags(addslashes($_POST['add3Box'])) . "', '"
                    . strip_tags(addslashes($_POST['postcodeBox'])) . "', '"
                    . strip_tags(addslashes($_POST['phoneBox'])) . "', '"
                    . strip_tags(addslashes($_POST['emailBox'])) . "')'";

         mysql_query($addsql);

         $setaddsql = "UPDATE zamowienia SET id_adresu_przesylki = " . mysql_insert_id() . ", status = 1 WHERE id = " . $_SESSION['SESS_ORDERNUM'];
         mysql_query($setaddsql);

         header("Location: " . $config_basedir . "placenie.php");
       }
       else
       {
         $custsql = "UPDATE zamowienia SET id_adresu_przesylki = 0,  status = 1 WHERE id = " . $_SESSION['SESS_ORDERNUM'];
         mysql_query($custsql);

         header("Location: " . $config_basedir . "placenie.php");
       }
    }
    else
    {
      if(empty($_POST['forenameBox']) ||
          empty($_POST['surenameBox']) ||
          empty($_POST['add1Box']) ||
          empty($_POST['add2Box']) ||
          empty($_POST['add3Box']) ||
          empty($_POST['postcodeBox']) ||
          empty($_POST['phoneBox']) ||
          empty($_POST['emailBox']))
      {
         header("Location: " . "placenie_adres.php?error=1");
         exit;
      }
      $addsql = "INSERT INTO id_adresu_przesylki(imie, nazwisko, adres1, adres2, adres3, kod_pocztowy, telefon, email)
                    VALUES('"
                    . $_POST['forenameBox'] . "', '"
                    . $_POST['surenameBox'] . "', '"
                    . $_POST['add1Box'] . "', '"
                    . $_POST['add2Box'] . "', '"
                    . $_POST['add3Box'] . "', '"
                    . $_POST['postcodeBox'] . "', '"
                    . $_POST['phoneBox'] . "', '"
                    . $_POST['emailBox'] . "')'";

      mysql_query($addsql);

      $setaddsql = "UPDATE zamowienia SET id_adresu_przesylki = " . mysql_insert_id() . ", status = 1 WHERE sesja = '" . session_id() . "'";
      mysql_query($setaddsql);

      header("Location: " . $config_basedir . "placenie.php");
    }
  }
  else
  {
    require("naglowek.php");
    echo "<h1>Dodawanie adresu przesyłki</h1>";

    if(isset($_GET['error']) == TRUE) {
      echo "<strong>Proszę umieścićw formularzu brakujące informacje</strong>";
    }
    echo "<form action='" . $SCRIPT_NAME . "'method='POST'>";

    if($_SESSION['SESS_LOGGEDIN'])
    {
    ?>
     
    <input type="radio" name="addselecBox" value="1" checked >Użyj adresu powiązanego z kontem</input><br>
    <input type="radio" name="addselecBox" value="2" checked >Użyj poniższego adresu:</input>
    <?php
    }
    ?>
    <table>
      <tr>
        <td> Imię</td>
        <td>
          <input type="text" name="forenameBox"></input>
        </td>
      </tr>
      <tr>
        <td>Nazwisko</td>
        <td>
          <input type="text" name="surenameBox"></input>
        </td>
      </tr><tr>
        <td>Numer domu, ulica</td>
        <td>
          <input type="text" name="add1Box"></input>
        </td>
      </tr><tr>
        <td>Miasto/Kraj</td>
        <td>
          <input type="text" name="add2Box"></input>
        </td>
      </tr><tr>
        <td>Wojewódzctwo</td>
        <td>
          <input type="text" name="add3Box"></input>
        </td> 
      </tr><tr>
        <td>Kod pocztowy </td>
        <td>
          <input type="text" name="postcodeBox"></input>
        </td>
      </tr><tr>
        <td>Telefon</td>
        <td>
          <input type="text" name="phoneBox"></input>
        </td>
      </tr><tr>
        <td>E-mail</td>
        <td>
          <input type="text" name="emailBox"></input>
        </td>
      </tr><tr>
        <td></td>
        <td>
          <input type="submit" name="submit" value="Dodaj adres (należy kliknąć tylko raz)"></input>
        </td>
      </tr>
    </table>
  </form>  

<?php
  }
  require("stopka.php");
?>
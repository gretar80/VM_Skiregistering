<?php
session_start();
include 'header.php';
include 'klasser.php';
if( empty($_SESSION['loggetInn']) || !$_SESSION['loggetInn'] ){
    echo 'Du er ikke logget på! Trykk her for å logge på:<br>';
    echo '<a href="loginn.php">Logg inn</a>';
    include 'footer.php';
    die();
}


// vi bruker denne variabelen til å huske hva var skrevet hvis det oppstår en feil
$eID = isset($_POST["eventID"])?$_POST["eventID"]:"";
$beskrivelse = isset($_POST["ny_beskrivelse"])?$_POST["ny_beskrivelse"]:"";

// variabler for denne tidspunkt
$dato = new DateTime( date('Y-m-d H:i') );
$dag = $dato->format('d');
$måned = $dato->format('m');
$år = $dato->format('Y');
$idag = $år."-".$måned."-".$dag;    // brukt for autofill i Dato-feltet

$resultat = "";

// oppretter to objekter
$event = new Event();
$database = new DBOperasjoner();

if( isset($_POST['slett'])){
    if( $eID == "" )
        $resultat = "Feil - du må skrive ID nummer";
    elseif( !preg_match("/^[0-9]{1,10}$/", $eID) )
        $resultat = "Feil - Øvelse-ID må ha 1-10 siffer";
    else{
        $event->set_eID($_POST['eventID']);
        $eID = "";
        $resultat = $database->removeEvent( $event );
    }
}

if( isset($_POST['endre_dato'])){
    if( $_POST['ny_dato'] == "" )
        $resultat = "Feil - du må velge gyldig dato";
    elseif( $_POST['eventID'] == "" )
        $resultat = "Feil - du må skrive ID nummer";
    else{
        $event->set_eID($_POST['eventID']);
        $nyDato = $_POST['ny_dato'];
        $eID = "";
        $resultat = $database->changeDate($event, $nyDato);
    }
}

if( isset($_POST['endre_tid'])){
    if( $_POST['ny_tid'] == "Velg" )
        $resultat = "Feil - du må velge tid";
    elseif( $_POST['eventID'] == "" )
        $resultat = "Feil - du må skrive ID nummer";
    else{
        $event->set_eID($_POST['eventID']);
        $nyTid = $_POST['ny_tid'];
        $eID = "";
        $resultat = $database->changeTime($event, $nyTid);
    }
}

if( isset($_POST['endre_beskrivelse'])){
    if( $_POST['ny_beskrivelse'] == "" )
        $resultat = "Feil - du må skrive noe beskrivelse";
    elseif( $_POST['eventID'] == "" )
        $resultat = "Feil - du må skrive ID nummer"; 
    elseif( !preg_match("/^[a-zæøåA-ZÆØÅ ]{2,30}$/", $_POST['ny_beskrivelse']) )
       $resultat = "Feil - Beskrivelse må ha 2-30 norske bokstaver";
    else{
        $event->set_eID($_POST['eventID']);
        $nyBeskrivelse = $_POST['ny_beskrivelse'];
        $eID = "";
        $beskrivelse = "";
        $resultat = $database->changeDescription($event, $nyBeskrivelse);
    }
}
?>
<article>
    <h2>Endre øvelse</h2>
    <form action="" method="post">
        <table>
            <tr>
                <td>Øvelse-ID:</td>
                <td><input type="text" name="eventID" value="<?php echo htmlspecialchars($eID); ?>"/></td>
                <td><input type="submit" name="slett" value="Slett øvelse"</td>
            </tr>
            <tr>
                <td>Ny dato:</td>
                <td><input type="date" name="ny_dato" value="<?php echo htmlspecialchars($idag); ?>"/></td>
                <td><input type="submit" name="endre_dato" value="Endre dato"</td>
            </tr>
            <tr>
                <td>Ny tid:</td>
                <td>
                    <select name="ny_tid">
                        <option>Velg</option>
                        <?php
                        for ($i = 10; $i <= 19; $i++){
                            for ($j = 0; $j <= 45; $j+=15)    {
                                echo '<option>'.$i.':'.str_pad($j, 2, '0', STR_PAD_LEFT).'</option>';
                            }
                        }
                        ?>
                    </select>
                </td>
                <td><input type="submit" name="endre_tid" value="Endre tid"</td>
            </tr>
            <tr>
                <td>Ny beskrivelse:</td>
                <td><input type="text" name="ny_beskrivelse" value="<?php echo htmlspecialchars($beskrivelse);?>"/></td>
                <td><input type="submit" name="endre_beskrivelse" value="Endre beskrivelse"</td>
            </tr>
        </table>
        <br>
        <br>
        Resultat:
        <br>
        <textarea rows="10" cols="50" readonly="readonly"><?php echo htmlspecialchars($resultat);?></textarea>
    </form>
</article>
<?php
include 'footer.php';
?>
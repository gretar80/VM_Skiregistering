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
// vi bruker disse variabler så feltene blir ikke tom hvis
// vi får en feil når vi trekker på knappen
$fnavn = isset($_POST["fnavn"])?$_POST["fnavn"]:"";
$enavn = isset($_POST["enavn"])?$_POST["enavn"]:"";
$adresse = isset($_POST["adresse"])?$_POST["adresse"]:"";
$postnr = isset($_POST["postnr"])?$_POST["postnr"]:"";
$poststed = isset($_POST["poststed"])?$_POST["poststed"]:"";
$telefonnr = isset($_POST["telefonnr"])?$_POST["telefonnr"]:"";
$eID = isset($_POST["eID"])?$_POST["eID"]:"";

$resultat = "";

if( isset($_POST["register"])){
    if( $_POST["fnavn"] == "" || $_POST["enavn"] == "" || $_POST["adresse"] == "" || $_POST["postnr"] == "" || $_POST["poststed"] == "" || $_POST["eID"] == "" || $_POST["telefonnr"] == "")
        $resultat = "Feil - du skrive i alle felt";
    elseif( !preg_match("/^[a-zæøåA-ZÆØÅ ]{2,20}$/", $fnavn) )
        $resultat = "Feil - Fornavn må ha 2-20 norske bokstaver";
    elseif( !preg_match("/^[a-zæøåA-ZÆØÅ ]{2,30}$/", $enavn) )
        $resultat = "Feil - Etternavn må ha 2-30 norske bokstaver";
    elseif( !preg_match("/^[0-9a-zæøåA-ZÆØÅ ,.\-]{4,40}$/", $adresse) )
        $resultat = "Feil - Gateadresse må ha 4-40 norske bokstaver";
    elseif( !preg_match("/^[0-9]{4}$/", $postnr) )
        $resultat = "Feil - Postnummer må ha 4 siffer";
    elseif( !preg_match("/^[a-zæøåA-ZÆØÅ ]{2,20}$/", $poststed) )
        $resultat = "Feil - Poststed må ha 2-20 norske bokstaver";
    elseif( !preg_match("/^[0-9]{8}$/", $telefonnr) )
        $resultat = "Feil - Telefonnummer må ha 8 siffer";
    elseif( !preg_match("/^[0-9]{1,10}$/", $eID) )
        $resultat = "Feil - Øvelse-ID må ha 1-10 siffer";
    elseif( $_REQUEST['type'] == "Velg" )
        $resultat = "Feil - du må velge billetttype";
    else{
        $viewer = new Viewer();
        $viewer->set_fornavn($fnavn);
        $viewer->set_etternavn($enavn);
        $viewer->set_adresse($adresse);
        $viewer->set_postnr($postnr);
        $viewer->set_poststed($poststed);
        $viewer->set_telefonnr($telefonnr);
        $viewer->set_eventID($eID);
        $viewer->set_value($_POST['type']);
        
        $datebase = new DBOperasjoner();
        
        // sjekk hvis øvelse-id eksisterer
        if( $datebase->finnEvent($viewer->get_eventID())){
            $resultat = $datebase->skrivPersonTilDatabase($viewer);

            $fnavn = "";
            $enavn = "";
            $adresse = "";
            $postnr = "";
            $poststed = "";
            $telefonnr = "";
            $eID = "";
        }
        else
            $resultat = "Finner ikke øvelse med ID = ".$viewer->get_eventID();
    }
}
?>

<article>
    <h2>Registrere publikum</h2>
    <form action="" method="post">
        <table>
            <tr>
                <td>Fornavn:</td>
                <td><input type="text" name="fnavn" value="<?php echo htmlspecialchars($fnavn); ?>" /></td>
            </tr>
            <tr>
                <td>Etternavn:</td>
                <td><input type="text" name="enavn" value="<?php echo htmlspecialchars($enavn); ?>"/></td>
            </tr>
            <tr>
                <td>Gateadresse</td>
                <td><input type="text" name="adresse" value="<?php echo htmlspecialchars($adresse); ?>"/></td>
            </tr>
            <tr>
                <td>Postnummer:</td>
                <td><input type="text" name="postnr" value="<?php echo htmlspecialchars($postnr); ?>"/></td>
            </tr>
            <tr>
                <td>Poststed:</td>
                <td><input type="text" name="poststed" value="<?php echo htmlspecialchars($poststed); ?>"/></td>
            </tr>
            <tr>
                <td>Telefonnummer: (+47)</td>
                <td><input type="text" name="telefonnr" value="<?php echo htmlspecialchars($telefonnr); ?>"/></td>
            </tr>
            <tr>
                <td>Billetttype:</td>
                <td>
                    <select name="type">
                        <option>Velg</option>
                        <option>Voksen</option>
                        <option>Barn</option>
                        <option>Student</option>
                        <option>Honnør</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Øvelse-ID:</td>
                <td><input type="text" name="eID" value="<?php echo htmlspecialchars($eID); ?>"/></td>
            </tr>            
        </table>
         <input type="submit" value="Register" name="register"/>
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
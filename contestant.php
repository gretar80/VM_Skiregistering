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
$nasjonalitet = isset($_POST["land"])?$_POST["land"]:"";
$eID = isset($_POST["eID"])?$_POST["eID"]:"";

$resultat = "";

if( isset($_POST["register"])){
    if( $_POST["fnavn"] == "" || $_POST["enavn"] == "" || $_POST["adresse"] == "" || $_POST["postnr"] == "" || $_POST["poststed"] == "" || $_POST["eID"] == "" || $_POST["telefonnr"] == "")
        $resultat = "Feil - du skrive i alle felt";
    elseif( !preg_match("/^[a-zæøåA-ZÆØÅ ]{2,20}$/", $fnavn) )
        $resultat = "Feil - Fornavn må ha 2-20 norske bokstaver";
    elseif( !preg_match("/^[a-zæøåA-ZÆØÅ ]{2,30}$/", $enavn) )
        $resultat = "Feil - Navn må ha 2-30 norske bokstaver";
    elseif( !preg_match("/^[0-9a-zæøåA-ZÆØÅ ,.\-]{4,40}$/", $adresse) )
        $resultat = "Feil - Gateadresse må ha 4-40 norske bokstaver";
    elseif( !preg_match("/^[0-9]{4}$/", $postnr) )
        $resultat = "Feil - Postnummer må ha 4 siffer";
    elseif( !preg_match("/^[a-zæøåA-ZÆØÅ ]{2,20}$/", $poststed) )
        $resultat = "Feil - Poststed må ha 2-20 norske bokstaver";
    elseif( !preg_match("/^[0-9]{8}$/", $telefonnr) )
        $resultat = "Feil - Telefonnummer må ha 8 siffer";
    elseif( !preg_match("/^[a-zæøåA-ZÆØÅ ]{2,30}$/", $nasjonalitet) )
        $resultat = "Feil - Fornavn må ha 2-30 norske bokstaver";    
    elseif( !preg_match("/^[0-9]{1,10}$/", $eID) )
        $resultat = "Feil - Øvelse-ID må ha 1-10 siffer";
    else{
        $contestant = new Contestant();
        $contestant->set_fornavn($fnavn);
        $contestant->set_etternavn($enavn);
        $contestant->set_adresse($adresse);
        $contestant->set_postnr($postnr);
        $contestant->set_poststed($poststed);
        $contestant->set_telefonnr($telefonnr);
        $contestant->set_eventID($eID);
        $contestant->set_value($_POST['land']);
        
        $datebase = new DBOperasjoner();
        
        // sjekk hvis øvelse-id eksisterer
        if( $datebase->finnEvent($contestant->get_eventID())){
            $resultat = $datebase->skrivPersonTilDatabase($contestant);
        
            $fnavn = "";
            $enavn = "";
            $adresse = "";
            $postnr = "";
            $poststed = "";
            $telefonnr = "";
            $nasjonalitet = "";
            $eID = "";
        }
        else
            $resultat = "Finner ikke øvelse med ID = ".$contestant->get_eventID();
    }
}
?>

<article>
    <h2>Registrere utøver</h2>
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
                <td>Nasjonalitet:</td>
                <td><input type="text" name="land" value="<?php echo htmlspecialchars($nasjonalitet); ?>"/></td>
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
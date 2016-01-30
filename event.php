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
$beskrivelse = isset($_POST["beskrivelse"])?$_POST["beskrivelse"]:"";

// variabler for denne tidspunkt
$dato = new DateTime( date('Y-m-d H:i') );
$dag = $dato->format('d');
$måned = $dato->format('m');
$år = $dato->format('Y');
$idag = $år."-".$måned."-".$dag;    // brukt for autofill i Dato-feltet

$resultat = "";

if( isset($_POST['newEvent'])){
    if( $_POST['dato'] == "" )
        $resultat = "Feil - du må velge gyldig dato";
    elseif( $_POST['tid'] == "Velg" )
        $resultat = "Feil - du må velge tid";
    elseif( $_POST['beskrivelse'] == "" )
        $resultat = "Feil - du må skrive noe beskrivelse";
    elseif( !preg_match("/^[a-zæøåA-ZÆØÅ ]{2,30}$/", $_POST['beskrivelse']) ){
        $resultat = "Feil - Beskrivelse må ha 2-30 norske bokstaver";
    }
    else{
        $beskrivelse = $_POST['beskrivelse'];
        $valgtDag = $_POST['dato'];
        $valgtTid = $_POST['tid'];
        
        // tidspunkt in DATETIME format
        $tidspunkt = $valgtDag." ".$valgtTid;
        
        // oppretter to objekter
        $event = new Event();
        $database = new DBOperasjoner();
        
        // lagrer variablene
        $event->set_eTidspunkt($tidspunkt);
        $event->set_eType($beskrivelse);
        
        $resultat = $database->skrivEventTilDatabase($event);
        $beskrivelse = "";
    }
}
?>
<article>
    <h2>Registrere ny øvelse</h2>
    <p>(alle øvelser skjer mellom 10 og 20)</p>
    <form action="" method="post">
        <table>
            <tr>
                <td>Dato:</td>
                <td><input type="date" name="dato" value="<?php echo htmlspecialchars($idag); ?>"/></td>
            </tr>
            <tr>
                <td>Tid:</td>
                <td>
                    <select name="tid">
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
            </tr>
            <tr>
                <td>Beskrivelse:</td>
                <td><input type="text" name="beskrivelse" value="<?php echo htmlspecialchars($beskrivelse);?>"/></td>
            </tr>
        </table>
        <input type="submit" name="newEvent" value="Register"/>
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
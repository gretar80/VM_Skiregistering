<?php
session_start();
include 'header.php';
include 'klasser.php';
?>
<article>
    <h2>Oversikt</h2>
    <p>Skriv inn øvelse-id og trykk på "Vis publikum" til å vise hvilke  øvelser 
        som har hvilke utøvere, eller "Vis utøvere" til å vise hvilke  publikum 
        som har billetter  til  hvilke  øvelser.</p>
    <p>Du kan finne øvelse-id med å trykke på "Vis alle øvelser"</p>
    <form action="" method="post">
        <table>
            <tr>
                <td rowspan="3"><input type="text" name="eventID" placeholder="Øvelse-ID"></td>
                <td><input type="submit" name="showViewers" value="Vis publikum "/></td>
            </tr>
            <tr>
                <td><input type="submit" name="showContestants" value="Vis utøvere"/></td>
            </tr>
            <tr>
                <td><input type="submit" name="showEvents" value="Vis alle øvelser"/></td>
            </tr>
        </table>
    </form>
    <br/>
    <?php
    if( isset($_POST['showEvents'])){
        $database = new DBOperasjoner();
        $database->showAllEvents();
    }
    if( isset($_POST['showViewers'])){
        if( $_POST['eventID'] == "" )
            echo "Du må skrive ID nummer<br>";
        elseif( !preg_match("/^[0-9]{1,10}$/", $_POST['eventID']) )
            echo "Feil - Øvelse-ID må ha 1-10 siffer<br>";
        else{
            $database = new DBOperasjoner();
            $database->showViewers($_POST['eventID']);
        }
    }
    if( isset($_POST['showContestants'])){
        if( $_POST['eventID'] == "" )
            echo "Du må skrive ID nummer<br>";
        elseif( !preg_match("/^[0-9]{1,10}$/", $_POST['eventID']) )
            echo "Feil - Øvelse-ID må ha 1-10 siffer<br>";
        else{
            $database = new DBOperasjoner();
            $database->showContestants($_POST['eventID']);
        }
    }
    ?>
</article>
<?php
include 'footer.php';
?>

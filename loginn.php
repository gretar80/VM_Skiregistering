<?php 
session_start();
include 'header.php';
include 'klasser.php';

if( empty($_SESSION['loggetInn']) || !$_SESSION['loggetInn'] ){
    echo"";
}
else{
    echo 'Du er logget inn';
}
if( isset($_POST['sjekk'])){
    if( $_POST['brukernavn'] == "")
        echo "Du må skrive brukernavn";
    else{
        $db = new mysqli("localhost", "root", "", "oblig3"); 
        $brukernavn = $_POST['brukernavn'];
        $hash_sjekk = Hash('sha256', $_POST['passord']);
        
        $sql = "SELECT * FROM administrators WHERE username = '$brukernavn' AND password = '$hash_sjekk'";
        $db->query($sql);

        if ($db->affected_rows == 1){
            $_SESSION['loggetInn']=true;
            echo '<meta http-equiv="refresh" content="0">';
        }
        else{
            $_SESSION['loggetInn']=false;
            echo "Ikke korrekt brukernavn/passord";
        }
    }
}
?>
<article>
    <h2>Logg inn</h2>
    <form action="" method="post">
        <table>
            <tr>
                <td>Brukernavn:</td>
                <td><input type="text" name="brukernavn"/></td>
            </tr>
            <tr>
                <td>Passord:</td>
                <td><input type="password" name="passord"/></td>
            </tr>
        </table>
        <input type="submit" name="sjekk" value="Logg inn"/>
    </form>        
</article>
<?php
include 'footer.php';
?>

<?php
session_start();
include 'header.php';

$fnavn = isset($_POST["fnavn"])?$_POST["fnavn"]:"";
$enavn = isset($_POST["enavn"])?$_POST["enavn"]:"";
$adresse = isset($_POST["adresse"])?$_POST["adresse"]:"";
$postnr = isset($_POST["postnr"])?$_POST["postnr"]:"";
$poststed = isset($_POST["poststed"])?$_POST["poststed"]:"";
$brukernavn = isset($_POST["brukernavn"])?$_POST["brukernavn"]:"";
$passord = isset($_POST["passord1"])?$_POST["passord1"]:"";

$resultat = "";

if( isset($_POST["register"])){
    if( $_POST["fnavn"] == "" || $_POST["enavn"] == "" || $_POST["adresse"] == "" || $_POST["postnr"] == "" || $_POST["poststed"] == "" || $_POST["brukernavn"] == "" || $_POST["passord1"] == "" || $_POST["passord2"] == "")
        $resultat = "Feil - du skrive i alle felt";
    elseif( !preg_match("/^[a-zæøåA-ZÆØÅ\- ]{2,20}$/", $fnavn) )
        $resultat = "Feil - Fornavn må ha 2-20 norske bokstaver";
    elseif( !preg_match("/^[a-zæøåA-ZÆØÅ ]{2,30}$/", $enavn) )
        $resultat = "Feil - Etternavn må ha 2-30 norske bokstaver";
    elseif( !preg_match("/^[0-9a-zæøåA-ZÆØÅ ,.\-]{4,40}$/", $adresse) )
        $resultat = "Feil - Gateadresse må ha 4-40 norske bokstaver";
    elseif( !preg_match("/^[0-9]{4}$/", $postnr) )
        $resultat = "Feil - Postnummer må ha 4 siffer";
    elseif( !preg_match("/^[a-zæøåA-ZÆØÅ ]{2,20}$/", $poststed) )
        $resultat = "Feil - Poststed må ha 2-20 norske bokstaver";
    elseif( !preg_match("/^[0-9a-zA-Z]{2,20}$/", $brukernavn) )
        $resultat = "Feil - Brukernavn må ha 2-20 bokstaver";
    elseif( !preg_match("/^.*(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).*$/", $passord) )
        $resultat = "Feil - Passord må være minst 8 tegn langt og inneholde minst 1 liten bokstav, 1 stor bokstav og 1 siffer eller 1 spesialtegn";
    elseif( $passord != $_POST['passord2'] )
        $resultat = "Feil - Passord må være gjentatt";
    else{
        /*$salt = mcrypt_create_iv(30); // gir 30 tilfeldige tegn
        $passordTIlHash = $salt."".$_POST['passord1'];
        echo $salt."<br>";
        echo $_POST['passord1']."<br>";
        echo $passordTIlHash."<br>";
        $hashTilDb = Hash('sha256', $passordTilHash);
*/
        $hashTilDb = Hash('sha256', $_POST['passord1']);
        $db = new mysqli("localhost", "root", "", "oblig3");
        $db->autocommit(false);
        
        //$sql = "Insert into administrators (Fornavn,Etternavn,Adresse,Postnr,Poststed,username,password,salt)";
        //$sql .= " Values('$fnavn','$enavn','$adresse','$postnr','$poststed','$brukernavn','$hashTilDb','$salt')";
        $sql = "Insert into administrators (Fornavn,Etternavn,Adresse,Postnr,Poststed,username,password)";
        $sql .= " Values('$fnavn','$enavn','$adresse','$postnr','$poststed','$brukernavn','$hashTilDb')";
        $resultat = $db->query($sql);
        
        $ok = true; // variabel for commit eller rollback
        
        if(!$resultat){
            $ok = false;
            $resultat = "Feil, kunne ikke skrive til admin-databasen<br>";
        }
        else{
            if(mysqli_affected_rows($db) == 0){
                $ok = false;
                $resultat = "Feil, ingen rader registert i admin-databasen!<br>";
            }
        }

        if($ok){
            $db->commit();
            $resultat = "Admin registrert\n".
                   "Fornavn: ".$fnavn."\n".
                   "Etternavn: ".$enavn."\n".
                   "Gateadresse: ".$adresse."\n".
                   "Postnummer: ".$postnr."\n".
                   "Poststed: ".$poststed."\n".
                   "Brukernavn: ".$brukernavn."\n";
        }
        else{
            $db->rollback();
            $resultat = "Feil i innsettingen av øvelse i databasen";
        }
         
        mysqli_close($db);

        
        $fnavn = "";
        $enavn = "";
        $adresse = "";
        $postnr = "";
        $poststed = "";
        $brukernavn = "";
    }
}
?>
<script type="text/javascript">
    function sjekk(){
        var pass1 = document.skjema.passord1.value;
        var pass2 = document.skjema.passord2.value;
        
        if( !sjekkPassord() || pass1 != pass2 || pass1 == "" || pass2 == "" ){
            document.getElementById("passord1").style.backgroundColor = "white";
            document.getElementById("passord2").style.backgroundColor = "white";
        }
        else{
            document.getElementById("passord1").style.backgroundColor = "#5F5";
            document.getElementById("passord2").style.backgroundColor = "#5F5";
        }
    }
    
    function sjekkFornavn(){
        var fornavn = document.skjema.fnavn.value;
        var test = /^([a-zæøåA-ZÆØÅ\- ]{2,20})$/.test( fornavn );
        
        if( test || fornavn == "")
            document.getElementById("fnavn").innerHTML = "";
        else
            document.getElementById("fnavn").innerHTML = "Feil - Fornavn må ha 2-20 norske bokstaver";
    }
    
    function sjekkEtternavn(){
        var etternavn = document.skjema.enavn.value;
        var test = /^([a-zæøåA-ZÆØÅ ]{2,30})$/.test( etternavn );
        
        if( test || etternavn == "")
            document.getElementById("enavn").innerHTML = "";
        else
            document.getElementById("enavn").innerHTML = "Feil - Etternavn må ha 2-30 norske bokstaver";
    }
   
    function sjekkAdresse(){
        var adresse = document.skjema.adresse.value;
        var test = /^([0-9a-zæøåA-ZÆØÅ ,.\-]{4,40})$/.test( adresse );
        
        if( test || adresse == "")
            document.getElementById("adresse").innerHTML = "";
        else
            document.getElementById("adresse").innerHTML = "Feil - Adresse må ha 4-40 norske bokstaver";
    }
    
    function sjekkPostnr(){
        var postnr = document.skjema.postnr.value;
        var test = /^([0-9]{4})$/.test( postnr );
        
        if( test || postnr == "")
            document.getElementById("postnr").innerHTML = "";
        else
            document.getElementById("postnr").innerHTML = "Feil - Postnummer må ha 4 siffer";
    }
    
    function sjekkPoststed(){
        var poststed = document.skjema.poststed.value;
        var test = /^([a-zæøåA-ZÆØÅ \-]{2,20})$/.test( poststed );
        
        if( test || poststed == "")
            document.getElementById("poststed").innerHTML = "";
        else
            document.getElementById("poststed").innerHTML = "Feil - Poststed må ha 2-20 norske bokstaver";
    }
    
    function sjekkBrukernavn(){
        var brukernavn = document.skjema.brukernavn.value;
        var test = /^([0-9a-zæøåA-ZÆØÅ]{2,20})$/.test( brukernavn );
        
        if( test || brukernavn == "")
            document.getElementById("brukernavn").innerHTML = "";
        else
            document.getElementById("brukernavn").innerHTML = "Feil - Brukernavn må ha 2-20 tegn (bokstaver og/eller siffer)";
    }
    
    function sjekkPassord(){
        var passord = document.skjema.passord1.value;
        var test = /^.*(?=.{6,})(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).*$/.test( passord );
        
        if( test ){
            document.getElementById("pass1").innerHTML = "";
            return true;
        }
        else if (passord == ""){
            document.getElementById("pass1").innerHTML = "";
            return false;
        }
        else{
            document.getElementById("pass1").innerHTML = "Feil - se regel under";
            return false;
        }
    }
</script>
<article>
    <h2>Registrere administrator</h2>
    <form action="" method="post" name="skjema">
        <table>
            <tr>
                <td>Fornavn:</td>
                <td><input type="text" name="fnavn" onblur="sjekkFornavn();" value="<?php echo htmlspecialchars($fnavn); ?>" /></td>
                <td id="fnavn"></td>
            </tr>
            <tr>
                <td>Etternavn:</td>
                <td><input type="text" name="enavn" onblur="sjekkEtternavn();" value="<?php echo htmlspecialchars($enavn); ?>"/></td>
                <td id="enavn"></td>
            </tr>
            <tr>
                <td>Gateadresse:</td>
                <td><input type="text" name="adresse" onblur="sjekkAdresse();" value="<?php echo htmlspecialchars($adresse); ?>"/></td>
                <td id="adresse"></td>
            </tr>
            <tr>
                <td>Postnummer:</td>
                <td><input type="text" name="postnr" onblur="sjekkPostnr();" value="<?php echo htmlspecialchars($postnr); ?>"/></td>
                <td id="postnr"></td>
            </tr>
            <tr>
                <td>Poststed:</td>
                <td><input type="text" name="poststed" onblur="sjekkPoststed();" value="<?php echo htmlspecialchars($poststed); ?>"/></td>
                <td id="poststed"></td>
            </tr>
            <tr><td><br></td></tr>
            <tr>
                <td>Brukernavn:</td>
                <td><input type="text" name="brukernavn" onblur="sjekkBrukernavn();" value="<?php echo htmlspecialchars($brukernavn); ?>"></td>
                <td id="brukernavn"></td>
            </tr>
            <tr>
                <td>Passord:</td>
                <td><input type="password" name="passord1" id="passord1" onblur="sjekkPassord();"></td>
                <td id="pass1"></td>
            </tr>
            <tr>
                <td>Gjenta passord:</td>
                <td><input type="password" name="passord2" id="passord2" onkeyup="sjekk()" onfocus="sjekk();"></td>
            </tr>
        </table>
        <font size="1">Passord må være minst 8 tegn langt og inneholde minst 1 liten bokstav, 1 stor bokstav og 1 siffer eller 1 spesialtegn</font>
        <br/>
        <br/>
        <input type="submit" value="Register" name="register"/>
        <br>
        <br>
        Resultat:
        <br>
        <textarea rows="8" cols="50" readonly="readonly" name="textarea"><?php echo htmlspecialchars($resultat);?></textarea>
    </form>
</article>



<?php
include 'footer.php';
?>

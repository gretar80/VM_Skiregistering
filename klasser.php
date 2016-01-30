<?php
class Event{
    private $eID;
    private $eTidspunkt;
    private $eType;
    
    public function set_eID($eID){
        $this->eID = $eID;
    }
    
    public function get_eID(){
        return $this->eID;
    }
    
    public function set_eTidspunkt($tid){
    $this->eTidspunkt = $tid;
    }

    public function get_eTidspunkt(){
        return $this->eTidspunkt;
    }

    public function set_eType($type){
        $this->eType = $type;
    }
    
    public function get_eType(){
        return $this->eType;
    }
}

class Person{
    private $pID;
    private $fornavn;
    private $etternavn;
    private $adresse;
    private $postnr;
    private $poststed;
    private $telefonnr;
    private $eventID;
    
    public function set_pID($pID){
        $this->pID = $pID;
    }

    public function get_pID(){
        return $this->pID;
    }
    
    public function set_fornavn($fnavn){
        $this->fornavn = $fnavn;
    }

    public function get_fornavn(){
        return $this->fornavn;
    }

    public function set_etternavn($enavn){
        $this->etternavn = $enavn;
    }

    public function get_etternavn(){
        return $this->etternavn;
    }
    
    public function set_adresse($adresse){
        $this->adresse = $adresse;
    }
    
    public function get_adresse(){
        return $this->adresse;
    }
    
    public function set_postnr($postnr){
        $this->postnr = $postnr;
    }
    
    public function get_postnr(){
        return $this->postnr;
    }
    
    public function set_poststed($poststed){
        $this->poststed = $poststed;
    }
    
    public function get_poststed(){
        return $this->poststed;
    }
    
    public function set_telefonnr($telefonnr){
        $this->telefonnr = $telefonnr;
    }
    
    public function get_telefonnr(){
        return $this->telefonnr;
    }

    public function set_eventID($eventID){
        $this->eventID = $eventID;
    }
    
    public function get_eventID(){
        return $this->eventID;
    }
}

class Viewer extends Person{
    private $billetttype;
    
    public function set_value($type){
        $this->billetttype = $type;
    }
    
    public function get_value(){
        return $this->billetttype;
    }
}


class Contestant extends Person{
    private $nasjonalitet;
    
    public function set_value($land){
        $this->nasjonalitet = $land;
    }
    
    public function get_value(){
        return $this->nasjonalitet;
    }   
}


class DBOperasjoner{
    
    public function skrivEventTilDatabase($event){        

        $db = new mysqli("localhost","root","","oblig2");
        $db->autocommit(false);
        
        if(!$db){
            die("Kunne ikke knytte til databasen");
        }
        elseif($db->connect_error){
            die("Kunne ikke åpne databasen ".$db->connect_error);
        }
        
        // event variabler
        $eTidspunkt = $event->get_eTidspunkt();
        $eType = $event->get_eType();
        
        $ok = true; // variabel for commit eller rollback

        
        // prøver å skrive i event-databasen
        $sql = "Insert into event (Dato,Type) Values('$eTidspunkt','$eType')";
        $resultat = $db->query($sql);

        if(!$resultat){
            $ok = false;
            return "Feil, kunne ikke skrive til event-databasen<br>";
        }
        else{
            if(mysqli_affected_rows($db) == 0){
                $ok = false;
                return "Feil, ingen rader registert i event-databasen!<br>";
            }
            else{
                $event->set_eID( $db->insert_id );
            }
        }

        if($ok){
            $db->commit();
            return "Øvelse registrert\n".
                   "Beskrivelse: ".$eType."\n".
                   "Tidspunkt: ".$eTidspunkt."\n".
                   "Øvelse-ID: ".$event->get_eID()."\n";
        }
        else{
            $db->rollback();
            return "Feil i innsettingen av øvelse i databasen";
        }
         
        mysqli_close($db);
    }
    
//***************************************************************************//

    
    public function finnEvent( $id ){
        $db = new mysqli("localhost","root","","oblig2");
        $db->autocommit(false);
        
        if(!$db){
            die("Kunne ikke knytte til databasen");
        }
        elseif($db->connect_error){
            die("Kunne ikke åpne databasen ".$db->connect_error);
        }
        
        $sql = "Select * From event Where EventID = '$id'";
        $resultat = $db->query($sql);

        if(!$resultat){
            return false;
        }
        else{
            if(mysqli_affected_rows($db) == 0){
                return false;
            }
            else{
                return true;
            }
        }      
        mysqli_close($db);
    }  
    
 //***************************************************************************//   

    public function skrivPersonTilDatabase($person){        
        $db = new mysqli("localhost","root","","oblig2");
        $db->autocommit(false);
        
        if(!$db){
            die("Kunne ikke knytte til databasen");
        }
        elseif($db->connect_error){
            die("Kunne ikke åpne databasen ".$db->connect_error);
        }
        
        // person variabler
        $fornavn = $person->get_fornavn();
        $etternavn = $person->get_etternavn();
        $adresse = $person->get_adresse();
        $postnr = $person->get_postnr();
        $poststed = $person->get_poststed();
        $telefonnr = $person->get_telefonnr();
        $eventID = $person->get_eventID();

        
        // person-subtype variabler
        if($person instanceof Viewer){
            $tabelNavn = "viewer";
            $kolonnNavn = "Billetttype";
            $value = $person->get_value();
        }
        elseif($person instanceof Contestant){
            $tabelNavn = "contestant";
            $kolonnNavn = "Nasjonalitet";
            $value = $person->get_value();
        }
        
        $ok = true; // variabel for commit eller rollback

        // prøver å skrive i person-databasen
        $sql = "Insert into person (Fornavn,Etternavn,Adresse,Postnr,Poststed,Telefonnr,EventID)";
        $sql .= " Values('$fornavn','$etternavn','$adresse','$postnr','$poststed','$telefonnr','$eventID')";
        $resultat = $db->query($sql);

        if(!$resultat){
            $ok = false;
            return "Feil, kunne ikke skrive til person-databasen<br>";
        }
        else{
            if(mysqli_affected_rows($db) == 0){
                $ok = false;
                return "Feil, ingen rader registert i person-databasen!<br>";
            }
            else{
                $person->set_pID( $db->insert_id );
            }
        }       
        
        $pID = $person->get_pID();
        
        // prøver å skrive i viewer/contestant-databasen
        $sql = "Insert into $tabelNavn (PersonID,$kolonnNavn)";
        $sql .= " Values('$pID','$value')";
        $resultat = $db->query($sql);

        if(!$resultat){
            $ok = false;
            return "Feil, kunne ikke skrive til $tabelNavn-databasen<br>";
        }
        else{
            if(mysqli_affected_rows($db) == 0){
                $ok = false;
                return "Feil, ingen rader registert i $tabelNavn-databasen!<br>";
            }
        }
        
        
        if($ok){
            $db->commit();
            return "Person registrert\n".
                   "PersonID: ".$pID."\n".
                   "Fornavn: ".$fornavn."\n".
                   "Etternavn: ".$etternavn."\n".
                   "Gateadresse: ".$adresse."\n".
                   "Postnummer: ".$postnr."\n".
                   "Poststed: ".$poststed."\n".
                   "Telefonnummer: (+47)".$telefonnr."\n".
                   "Øvelse-ID: ".$eventID."\n".
                   "$kolonnNavn: ".$value."\n";
        }
        else{
            $db->rollback();
            return "Feil i innsettingen i databasen";
        }
         
        mysqli_close($db);
    }
    
//***************************************************************************//
    
    public function removeEvent( $event ){
    
        $db = new mysqli("localhost","root","","oblig2");
        $db->autocommit(false);
        
        if(!$db){
            die("Kunne ikke knytte til databasen");
        }
        elseif($db->connect_error){
            die("Kunne ikke åpne databasen ".$db->connect_error);
        }
        
        $ok = true; // variabel for commit eller rollback
        $utskrift = "";
        $antallPublikumFjernet = 0;
        $antallUtøvereFjernet = 0;
        $removeID = $event->get_eID();
        
        if( !$this->finnEvent($removeID) )
            return "Fant ikke øvelse med ID = $removeID\n";
        else{
            $sql = "Delete From event ";
            $sql .= " Where EventID = $removeID";
            $resultat = $db->query($sql);

            if(!$resultat){
                $ok = false;
                $utskrift .= "Feil, kunne ikke fjerne øvelse fra databasen\n";
            }
            else{
                if($db->affected_rows == 0){
                    $ok = false;
                    $utskrift .= "Fant ikke øvelse med ID = $removeID\n";
                }
            }

            $sql = "Delete From viewer ";
            $sql .= "Where PersonID In ( ";
            $sql .= "Select p.PersonID From person As p ";
            $sql .= "Where p.EventID = $removeID )";
            $resultat = $db->query($sql);

            if(!$resultat){
                $ok = false;
                $utskrift .= "Feil, kunne ikke fjerne publikum fra databasen\n";
            }
            else{
                if($db->affected_rows > 0)
                    $antallPublikumFjernet = $db->affected_rows;
            }

            $sql = "Delete From contestant ";
            $sql .= "Where PersonID In ( ";
            $sql .= "Select p.PersonID From person As p ";
            $sql .= "Where p.EventID = $removeID )";
            $resultat = $db->query($sql);

            if(!$resultat){
                $ok = false;
                $utskrift .= "Feil, kunne ikke fjerne utøvere fra databasen\n";
            }
            else{
                if($db->affected_rows > 0)
                    $antallUtøvereFjernet = $db->affected_rows;
            }
            
            $sql = "Delete From person ";
            $sql .= "Where EventID = $removeID";
            $resultat = $db->query($sql);

            if(!$resultat){
                $ok = false;
                $utskrift .= "Feil, kunne ikke fjerne person fra databasen\n";
            }
            else{
                if($db->affected_rows > 0)
                    $antallFjernet = $db->affected_rows;
            }

            if($ok){
                $db->commit();
                $utskrift .= "Øvelse med ID = $removeID fjernet\n";
                if( $antallPublikumFjernet > 0 )
                    $utskrift .= "Antall $antallPublikumFjernet tilskuere fjernet\n";
                if( $antallUtøvereFjernet > 0 )
                    $utskrift .= "Antall $antallUtøvereFjernet utøvere fjernet\n";
                if( $antallFjernet > 0 )
                    $utskrift .= "Total antall personer fjernet: $antallFjernet\n";
            }
            else{
                $db->rollback();
                $utskrift .= "Feil med fjerningen av øvelsen med ID = $removeID\n";
            }
        }
        
        mysqli_close($db);
        
        return $utskrift;
    }
    
    
//***************************************************************************//
    
    public function changeDate( $event, $dato ){
    
        $db = new mysqli("localhost","root","","oblig2");
        $db->autocommit(false);
        
        if(!$db){
            die("Kunne ikke knytte til databasen");
        }
        elseif($db->connect_error){
            die("Kunne ikke åpne databasen ".$db->connect_error);
        }
        
        $ok = true; // variabel for commit eller rollback
        
        $changeID = $event->get_eID();
        
        // først vi må finne den gamle verdien
        $sql = "Select Dato from event ";
        $sql .= " Where EventID = $changeID";
        $resultat = $db->query($sql);
        
        if(!$resultat){
            $ok = false;
            return "Feil, kunne ikke skrive til øvelse-databasen";
        }
        else{
            if($db->affected_rows == 0){
                $ok = false;
                return "Fant ikke øvelse med ID = $changeID";
            }
            else{
                $rad = $resultat->fetch_object();
                $nyDato = $dato."".substr($rad->Dato, 10);
                
                // nu må vi endre datoen
                $sql = "Update event ";
                $sql .= " Set Dato = '$nyDato' ";
                $sql .= " Where EventID = $changeID";
                $resultat = $db->query($sql);
                
            
                if(!$resultat){
                    $ok = false;
                    return "Feil, kunne ikke skrive til øvelse-databasen";
                }
                else{
                    if($db->affected_rows == 0){
                        $ok = false;
                        return "Fant ikke øvelse med ID = $changeID";
                    }
                }
            }
        }

        if($ok){
            $db->commit();
            return "Dato for øvelse med ID = $changeID\n".
                   "endret til: $nyDato";
        }
        else{
            $db->rollback();
            return "Feil med endringen av datoen i databasen";
        }
        
        mysqli_close($db);
    }    
    
//***************************************************************************//
    
    public function changeTime( $event, $tid ){
    
        $db = new mysqli("localhost","root","","oblig2");
        $db->autocommit(false);
        
        if(!$db){
            die("Kunne ikke knytte til databasen");
        }
        elseif($db->connect_error){
            die("Kunne ikke åpne databasen ".$db->connect_error);
        }
        
        $ok = true; // variabel for commit eller rollback
        
        $changeID = $event->get_eID();
        
        // først vi må finne den gamle verdien
        $sql = "Select Dato from event ";
        $sql .= " Where EventID = $changeID";
        $resultat = $db->query($sql);
        
        if(!$resultat){
            $ok = false;
            return "Feil, kunne ikke skrive til øvelse-databasen";
        }
        else{
            if($db->affected_rows == 0){
                $ok = false;
                return "Fant ikke øvelse med ID = $changeID";
            }
            else{
                $rad = $resultat->fetch_object();
                $nyTid = substr($rad->Dato,0,11 )."".$tid;
                
                // nu må vi endre tiden
                $sql = "Update event ";
                $sql .= " Set Dato = '$nyTid' ";
                $sql .= " Where EventID = $changeID";
                $resultat = $db->query($sql);
                
            
                if(!$resultat){
                    $ok = false;
                    return "Feil, kunne ikke skrive til øvelse-databasen";
                }
                else{
                    if($db->affected_rows == 0){
                        $ok = false;
                        return "Fant ikke øvelse med ID = $changeID";
                    }
                }
            }
        }

        if($ok){
            $db->commit();
            return "Dato for øvelse med ID = $changeID\n".
                   "endret til: $nyTid";
        }
        else{
            $db->rollback();
            return "Feil med endringen av datoen i databasen";
        }
        
        mysqli_close($db);
    }    
    
    
//***************************************************************************//
    
    public function changeDescription( $event, $text ){
    
        $db = new mysqli("localhost","root","","oblig2");
        $db->autocommit(false);
        
        if(!$db){
            die("Kunne ikke knytte til databasen");
        }
        elseif($db->connect_error){
            die("Kunne ikke åpne databasen ".$db->connect_error);
        }
        
        $ok = true; // variabel for commit eller rollback
        
        $changeID = $event->get_eID();
        
        $sql = "Update event ";
        $sql .= " Set Type = '$text' ";
        $sql .= " Where EventID = $changeID";
        $resultat = $db->query($sql);
        
        if(!$resultat){
            $ok = false;
            return "Feil, kunne ikke skrive til øvelse-databasen";
        }
        else{
            if($db->affected_rows == 0){
                $ok = false;
                return "Fant ikke øvelse med ID = $changeID";
            }
        }

        if($ok){
            $db->commit();
            return "Beskrivelse for øvelse med ID = $changeID\n".
                   "endret til: $text";
        }
        else{
            $db->rollback();
            return "Feil med endringen av datoen i databasen";
        }
        
        mysqli_close($db);
    }                
    
    
    
//***************************************************************************//
    
    public function showAllEvents(){
    
        $db = new mysqli("localhost","root","","oblig2");
        
        if(!$db){
            die("Kunne ikke knytte til databasen");
        }
        elseif($db->connect_error){
            die("Kunne ikke åpne databasen ".$db->connect_error);
        }
        
        $sql = "Select * From event";       
        $resultat = $db->query($sql);
        
        if(!$resultat){
            echo "Feil i lesning fra øvelse-databasen";
        }
        else{
            if($db->affected_rows == 0){
                echo "Ingen øvelser registrert";
            }
            else{
                $antallRader = $db->affected_rows;
                echo "<table border = '1.0'><tr><td>ØvelseID</td><td>Tidspunkt</td><td>Type</td>";
                
                for ($i=0;$i<$antallRader;$i++){
                    echo "<tr><td>";
                    $rad = $resultat->fetch_object();
                    echo $rad->EventID."</td><td>".$rad->Dato."</td><td>".$rad->Type."</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
        }
        $db->close();
    }                
    
    
    
//***************************************************************************//
    
    public function showViewers($id){
    
        $db = new mysqli("localhost","root","","oblig2");
        
        if(!$db){
            die("Kunne ikke knytte til databasen");
        }
        elseif($db->connect_error){
            die("Kunne ikke åpne databasen ".$db->connect_error);
        }
        
        if( !$this->finnEvent($id) )
            echo " Feil - ingen øvelse med ID = $id";
        else{
            $sql = "Select p.*, v.Billetttype From person As p, viewer As v ";
            $sql .= "Where p.PersonID = v.PersonID and p.EventID = $id";       
            $resultat = $db->query($sql);

            if(!$resultat){
                echo "Feil i lesning fra øvelse-databasen";
            }
            else{
                if($db->affected_rows == 0){
                    echo "Ingen person har billetter til øvelse med ID = $id";
                }
                else{
                    $antallRader = $db->affected_rows;
                    echo "<table border = '1.0'><tr><td>PersonID</td><td>Fornavn</td><td>Etternavn</td>";
                    echo "<td>Gateadresse</td><td>Postnr</td><td>Poststed</td>";
                    echo "<td>Telefonnr</td><td>Øvelse-ID</td><td>Billetttype</td>";

                    for ($i=0;$i<$antallRader;$i++){
                        echo "<tr><td>";
                        $rad = $resultat->fetch_object();
                        echo $rad->PersonID."</td><td>".$rad->Fornavn."</td><td>".$rad->Etternavn."</td>";
                        echo "<td>".$rad->Adresse."</td><td>".$rad->Postnr."</td><td>".$rad->Poststed."</td>";
                        echo "<td>".$rad->Telefonnr."</td><td>".$rad->EventID."</td><td>".$rad->Billetttype."</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
            }
        }
        $db->close();
    }                
    
    
    
//***************************************************************************//
    
    public function showContestants($id){
    
        $db = new mysqli("localhost","root","","oblig2");
        
        if(!$db){
            die("Kunne ikke knytte til databasen");
        }
        elseif($db->connect_error){
            die("Kunne ikke åpne databasen ".$db->connect_error);
        }
        if( !$this->finnEvent($id) )
            echo " Feil - ingen øvelse med ID = $id";
        else{
            $sql = "Select p.*, c.Nasjonalitet From person As p, contestant As c ";
            $sql .= "Where p.PersonID = c.PersonID and p.EventID = $id";       
            $resultat = $db->query($sql);

            if(!$resultat){
                echo "Feil i lesning fra øvelse-databasen";
            }
            else{
                if($db->affected_rows == 0){
                    echo "Ingen utøver er regisrert til øvelse med ID = $id";
                }
                else{
                    $antallRader = $db->affected_rows;
                    echo "<table border = '1.0'><tr><td>PersonID</td><td>Fornavn</td><td>Etternavn</td>";
                    echo "<td>Gateadresse</td><td>Postnr</td><td>Poststed</td>";
                    echo "<td>Telefonnr</td><td>Øvelse-ID</td><td>Nasjonalitet</td>";

                    for ($i=0;$i<$antallRader;$i++){
                        echo "<tr><td>";
                        $rad = $resultat->fetch_object();
                        echo $rad->PersonID."</td><td>".$rad->Fornavn."</td><td>".$rad->Etternavn."</td>";
                        echo "<td>".$rad->Adresse."</td><td>".$rad->Postnr."</td><td>".$rad->Poststed."</td>";
                        echo "<td>".$rad->Telefonnr."</td><td>".$rad->EventID."</td><td>".$rad->Nasjonalitet."</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
            }
        }
        $db->close();
    }                
}
?>
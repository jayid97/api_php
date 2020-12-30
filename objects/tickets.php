<?php
// 'ticket' object
class Ticket{
 
    // database connection and table name
    private $conn;
    private $table_name = "tickets";
 
    // object properties
    public $id;
    public $title;
    public $body;
    public $ticket_date;
    public $lat;
    public $lng;
    public $guest_email;
    public $Kes_Lokasi;
    public $userid;
    public $KesPengadu;
    public $KesUmur;
    public $KesBangsa;
    public $KesNoTelefon;
    public $KesJantina;
    public $KesAlamatLine1;
    public $KesAlamatLine2;
    public $KesAlamatBandar;
    public $KesAlamatPoskod;
    public $KesDaerah;
    
    

 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 
    // create new ticket record
    function create(){

        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    userid = :userid,
                    title = :title,
                    body = :body,
                    ticket_date = :ticket_date,
                    lat = :lat,
                    lng = :lng,
                    guest_email = :guest_email,
                    KesLokasi = :KesLokasi,
                    KesTajuk = :title,
                    KesUmur = :KesUmur,
                    KesPengadu = :KesPengadu,
                    KesBangsa = :KesBangsa,
                    KesNamaPengadu = :KesPengadu,
                    KesNoTelefon = :KesNoTelefon,
                    KesJantina = :KesJantina,
                    KesAlamatLine1 = :KesAlamatLine1,
                    KesAlamatLine2 = :KesAlamatLine2,
                    KesAlamatBandar = :KesAlamatBandar,
                    KesAlamatPoskod = :KesAlamatPoskod,
                    KesAlamat = :guest_email,
                    KesDaerah = :KesDaerah
                    ";
     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->guest_email=htmlspecialchars(strip_tags($this->guest_email));
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->body=htmlspecialchars(strip_tags($this->body));
        $this->lat=htmlspecialchars(strip_tags($this->lat));
        $this->lng=htmlspecialchars(strip_tags($this->lng));
        $this->KesLokasi=htmlspecialchars(strip_tags($this->KesLokasi));
        $this->userid=htmlspecialchars(strip_tags($this->userid));
        
     
        // bind the values
        $stmt->bindParam(':guest_email', $this->guest_email);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':body', $this->body);
        $stmt->bindParam(':lat', $this->lat);
        $stmt->bindParam(':lng', $this->lng);
        $stmt->bindParam(':KesLokasi', $this->KesLokasi);
        $stmt->bindParam(':userid', $this->userid);
        $stmt->bindParam(':ticket_date', $this->ticket_date);
        $stmt->bindParam(':KesPengadu', $this->KesPengadu);
        $stmt->bindParam(':KesUmur', $this->KesUmur);
        $stmt->bindParam(':KesBangsa', $this->KesBangsa);
        $stmt->bindParam(':KesNoTelefon', $this->KesNoTelefon);
        $stmt->bindParam(':KesJantina', $this->KesJantina);
        $stmt->bindParam(':KesAlamatLine1', $this->KesAlamatLine1);
        $stmt->bindParam(':KesAlamatLine2', $this->KesAlamatLine2);
        $stmt->bindParam(':KesAlamatBandar', $this->KesAlamatBandar);
        $stmt->bindParam(':KesAlamatPoskod', $this->KesAlamatPoskod);
        $stmt->bindParam(':KesAlamat', $this->KesAlamat);
        $stmt->bindParam(':KesDaerah', $this->KesDaerah);
        
        
        

     
        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }


     // GET ALL
     function getTickets(){
         

        $sqlQuery = "SELECT
                    ID,title,body,KesLokasi,ticket_date
                  FROM
                    ". $this->table_name ."
                WHERE 
                   guest_email = :guest_email
                ";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(':guest_email', $this->guest_email);

        $stmt->execute();

        return $stmt;
    }

    // READ single
    function getSingleTickets(){
        $sqlQuery = "SELECT
                    * 
                  FROM
                    ". $this->table_name ."
                WHERE 
                   ID = ?
                LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $this->ID);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->ID = $dataRow['ID'];
        $this->title = $dataRow['title'];
        $this->body = $dataRow['body'];
        $this->KesLokasi = $dataRow['KesLokasi'];
    }   

    // UPDATE
    function updateTickets(){
        $sqlQuery = "UPDATE
                    ". $this->table_name ."
                SET
                    body = :body, 
                    KesLokasi = :KesLokasi
                WHERE 
                    ID = :ID";
    
        $stmt = $this->conn->prepare($sqlQuery);
    
        $this->body=htmlspecialchars(strip_tags($this->body));
        $this->KesLokasi=htmlspecialchars(strip_tags($this->KesLokasi));
        $this->ID=htmlspecialchars(strip_tags($this->ID));
    
        // bind data
        $stmt->bindParam(":body", $this->body);
        $stmt->bindParam(":KesLokasi", $this->KesLokasi);
        $stmt->bindParam(":ID", $this->ID);
    
        if($stmt->execute()){
           return true;
        }
        return false;
    }

    // DELETE
    function deleteTicket(){
        $sqlQuery = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($sqlQuery);
    
        $this->ID=htmlspecialchars(strip_tags($this->ID));
    
        $stmt->bindParam(1, $this->ID);
    
        if($stmt->execute()){
            return true;
        }
        return false;
    }

}
?>
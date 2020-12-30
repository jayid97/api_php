<?php
// 'user' object
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "users";
 
    // object properties
    public $ID;
    public $username;
    public $IC;
    public $email;
    public $password;
    public $No_Jln_Lrg;
    public $Taman_Kampung;
    public $Bandar_Kawasan;
    public $Poskod;
    public $daerah;
    public $gender;
    public $joined_date;
    public $phoneNumber;
    public $user_role;
    public $race;
    public $joined;
    public $IP;
    public $age;
    public $first_name;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 
    // create new user record
    function create(){
     
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    username = :email,
                    IC = :IC,
                    email = :email,
                    password = :password,
                    No_Jln_Lrg = :No_Jln_Lrg,
                    Taman_Kampung = :Taman_Kampung,
                    Bandar_Kawasan = :Bandar_Kawasan,
                    Poskod = :Poskod,
                    daerah = :daerah,
                    gender = :gender, 
                    joined_date = :joined_date,
                    joined = :joined,
                    IP = :IP,
                    phoneNumber = :phoneNumber,
                    user_role = :user_role,
                    race = :race,
                    age = :age,
                    first_name = :first_name
                    ";
     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->IC=htmlspecialchars(strip_tags($this->IC));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->No_Jln_Lrg=htmlspecialchars(strip_tags($this->No_Jln_Lrg));
        $this->Taman_Kampung=htmlspecialchars(strip_tags($this->Taman_Kampung));
        $this->Bandar_Kawasan=htmlspecialchars(strip_tags($this->Bandar_Kawasan));
        $this->Poskod=htmlspecialchars(strip_tags($this->Poskod));
        $this->daerah=htmlspecialchars(strip_tags($this->daerah));
        $this->gender=htmlspecialchars(strip_tags($this->gender));
        $this->phoneNumber=htmlspecialchars(strip_tags($this->phoneNumber));
        $this->user_role=htmlspecialchars(strip_tags($this->user_role));
        $this->race=htmlspecialchars(strip_tags($this->race));
        $this->age=htmlspecialchars(strip_tags($this->age));
        $this->first_name=htmlspecialchars(strip_tags($this->first_name));
     
        // bind the values
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':IC', $this->IC);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':No_Jln_Lrg', $this->No_Jln_Lrg);
        $stmt->bindParam(':Taman_Kampung', $this->Taman_Kampung);
        $stmt->bindParam(':Bandar_Kawasan', $this->Bandar_Kawasan);
        $stmt->bindParam(':Poskod', $this->Poskod);
        $stmt->bindParam(':daerah', $this->daerah);
        $stmt->bindParam(':gender', $this->gender);
        $stmt->bindParam(':phoneNumber', $this->phoneNumber);
        $stmt->bindParam(':user_role', $this->user_role);
        $stmt->bindParam(':race', $this->race);
        $stmt->bindParam(':joined_date', $this->joined_date);
        $stmt->bindParam(':joined', $this->joined);
        $stmt->bindParam(':IP', $this->IP);
        $stmt->bindParam(':age', $this->age);
        $stmt->bindParam(':first_name', $this->first_name);

     
        // hash the password before saving to database
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
     
        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }
     
    // check if given email exist in the database
function emailExists(){

	// query to check if email exists
	$query = "SELECT ID, username, IC, password, age, 
    first_name, race, phoneNumber, gender,No_Jln_Lrg,Taman_Kampung,
    Bandar_Kawasan,Poskod,daerah
			FROM " . $this->table_name . "
			WHERE email = ?
			LIMIT 0,1";

	// prepare the query
	$stmt = $this->conn->prepare( $query );

	// sanitize
	$this->email=htmlspecialchars(strip_tags($this->email));

	// bind given email value
	$stmt->bindParam(1, $this->email);

	// execute the query
	$stmt->execute();

	// get number of rows
	$num = $stmt->rowCount();

	// if email exists, assign values to object properties for easy access and use for php sessions
	if($num>0){

		// get record details / values
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// assign values to object properties
		$this->ID = $row['ID'];
        $this->username = $row['username'];
        $this->age = $row['age'];
        $this->first_name = $row['first_name'];
        $this->race = $row['race'];
        $this->gender = $row['gender'];
        $this->No_Jln_Lrg = $row['No_Jln_Lrg'];
        $this->Bandar_Kawasan = $row['Bandar_Kawasan'];
        $this->Taman_Kampung = $row['Taman_Kampung'];
        $this->Poskod = $row['Poskod'];
        $this->daerah = $row['daerah'];
        $this->phoneNumber = $row['phoneNumber'];
		$this->IC = $row['IC'];
		$this->password = $row['password'];

		// return true because email exists in the database
		return true;
	}

	// return false if email does not exist in the database
	return false;
}

// READ single
function getProfile(){
    $sqlQuery = "SELECT
                * 
              FROM
                ". $this->table_name ."
            WHERE 
               email = ?
            LIMIT 0,1";

    $stmt = $this->conn->prepare($sqlQuery);

    $stmt->bindParam(1, $this->email);

    $stmt->execute();

    $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $this->ID = $dataRow['ID'];
    $this->email = $dataRow['email'];
    $this->username = $dataRow['username'];
    $this->phoneNumber = $dataRow['phoneNumber'];
    $this->gender = $dataRow['gender'];
    $this->race = $dataRow['race'];
    $this->password = $dataRow['password'];
} 

function updateUsers(){
    $sqlQuery = "UPDATE
                ". $this->table_name ."
            SET
                username = :username,
                email = :email,
                phoneNumber = :phoneNumber,
                race = :race,
                gender = :gender
            WHERE 
                ID = :ID";

    $stmt = $this->conn->prepare($sqlQuery);

    $this->username=htmlspecialchars(strip_tags($this->username));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->race=htmlspecialchars(strip_tags($this->race));
    $this->phoneNumber=htmlspecialchars(strip_tags($this->phoneNumber));
    $this->gender=htmlspecialchars(strip_tags($this->gender));
    $this->ID=htmlspecialchars(strip_tags($this->ID));

    // bind data
    $stmt->bindParam(":ID", $this->ID);
    $stmt->bindParam(":username", $this->username);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":race", $this->race);
    $stmt->bindParam(":gender", $this->gender);
    $stmt->bindParam(":phoneNumber", $this->phoneNumber);

    if($stmt->execute()){
       return true;
    }
    return false;
}

function updatePass(){
    $sqlQuery = "UPDATE
                ". $this->table_name ."
            SET
                password = :password
            WHERE 
                email = :email";

    $stmt = $this->conn->prepare($sqlQuery);

   
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->password=htmlspecialchars(strip_tags($this->password));

    // bind data
    $stmt->bindParam(":email", $this->email);
    
      // hash the password before saving to database
      $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
      $stmt->bindParam(':password', $password_hash);


    if($stmt->execute()){
       return true;
    }
    return false;
}

}
?>
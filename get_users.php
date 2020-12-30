<?php
// required headers
if (isset($_SERVER['HTTP_ORIGIN'])) {

    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Access-Control-Allow-Methods: GET");
    header('Access-Control-Allow-Credentials: true');
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}




// files needed to connect to database
include_once 'config/database.php';
include_once 'objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate product object
$users = new User($db);
 
$users->email = isset($_GET['email']) ? $_GET['email'] : die();
  
    $users->getProfile();

    if($users->email != null){
        // create array
        $users_arr = array(
            "ID" =>  $users->ID,
            "email" => $users->email,
            "username" => $users->username,
            "phoneNumber" => $users->phoneNumber,
            "gender" => $users->gender,
            "race" => $users->race,
            "password" =>$users->password,
        );
      
        http_response_code(200);
        echo json_encode($users_arr);
    }
      
    else{
        http_response_code(404);
        echo json_encode("Ticket not found.");
    }
 

?>
<?php
// required headers
if (isset($_SERVER['HTTP_ORIGIN'])) {

    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Access-Control-Allow-Methods: POST");
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
$user = new User($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// set product property values
$user->username = $data->username;
$user->IC = $data->IC;
$user->email = $data->email;
$user->password = $data->password;
$user->No_Jln_Lrg = $data->No_Jln_Lrg;
$user->Taman_Kampung = $data->Taman_Kampung;
$user->Bandar_Kawasan = $data->Bandar_Kawasan;
$user->Poskod = $data->Poskod;
$user->daerah = $data->daerah;
$user->gender = $data->gender;
$user->phoneNumber = $data->phoneNumber;
$user->user_role = $data->user_role;
$user->race = $data->race;
$user->joined_date = date("n-Y");
$user->joined = time();
$user->IP = $_SERVER['REMOTE_ADDR'];
$user->age = $data->age;
$user->first_name = $data->first_name;

// create the user
if(
    !empty($user->IC) &&
    !empty($user->email) &&
    !empty($user->password) &&
    $user->create()
){
 
    // set response code
    http_response_code(200);
 
    // display message: user was created
    echo json_encode(array("message" => "User was created."));
}
 
// message if unable to create user
else{
 
    // set response code
    http_response_code(400);
 
    // display message: unable to create user
    echo json_encode(array("message" => "Unable to create user."));
}
?>
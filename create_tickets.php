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

// generate json web token
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;


// files needed to connect to database
include_once 'config/database.php';
include_once 'objects/tickets.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate product object
$ticket = new Ticket($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));

// get jwt
$jwt=isset($data->jwt) ? $data->jwt : "";

// if jwt is not empty
if($jwt){

    // if decode succeed, show user details
    try {

        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));

        echo json_encode(array(
            "message" => "Access granted.",
            "ID" => $decoded->data->ID,
            "email" => $decoded->data->email,
            "age" =>$decoded->data->age,
            "first_name"=>$decoded->data->first_name,
            "race"=>$decoded->data->race,
            "gender"=>$decoded->data->gender,
            "phoneNumber"=>$decoded->data->phoneNumber,
            "No_Jln_Lrg"=>$decoded->data->No_Jln_Lrg,
            "Taman_Kampung"=>$decoded->data->Taman_Kampung,
            "Bandar_Kawasan"=>$decoded->data->Bandar_Kawasan,
            "Poskod"=>$decoded->data->Poskod,
            "Daerah"=>$decoded->data->daerah,
        ));

                // set product property values
        $ticket->userid = $decoded->data->ID;
        $ticket->guest_email = $decoded->data->email;
        $ticket->title = $data->title;
        $ticket->body = $data->body;
        $ticket->lat = $data->lat;
        $ticket->lng = $data->lng;
        $ticket->KesLokasi = $data->KesLokasi;
        $ticket->ticket_date = date("d-m-Y");
        $ticket->KesUmur = $decoded->data->age;
        $ticket->KesPengadu = $decoded->data->first_name;
        $ticket->KesBangsa = $decoded->data->race;
        $ticket->KesNoTelefon = $decoded->data->phoneNumber;
        $ticket->KesJantina = $decoded->data->gender;
        $ticket->KesAlamatLine1 = $decoded->data->No_Jln_Lrg;
        $ticket->KesAlamatLine2 = $decoded->data->Taman_Kampung;
        $ticket->$KesAlamatBandar = $decoded->data->Bandar_Kawasan;
        $ticket->KesAlamatPoskod = $decoded->data->Poskod;
        $ticket->KesDaerah = $decoded->data->daerah;
     

        // create the ticket
        if(
            !empty($ticket->title) &&
            !empty($ticket->body) &&
            $ticket->create()
        ){
        
            // set response code
            http_response_code(200);
        
            // display message: ticket was created
            echo json_encode(array("message" => "ticket was created."));
        }
        
        // message if unable to create ticket
        else{
        
            // set response code
            http_response_code(400);
        
            // display message: unable to create ticket
            echo json_encode(array("message" => "Unable to create ticket."));
        }
            }

            // if decode fails, it means jwt is invalid
        catch (Exception $e){

            // set response code
            http_response_code(401);

            // show error message
            echo json_encode(array(
                "message" => "Access denied.",
                "error" => $e->getMessage()
            ));
        }
}







?>
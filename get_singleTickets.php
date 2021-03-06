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
include_once 'objects/tickets.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate product object
$ticket = new Ticket($db);
 
$ticket->ID = isset($_GET['ID']) ? $_GET['ID'] : die();
  
    $ticket->getSingleTickets();

    if($ticket->title != null){
        // create array
        $ticket_arr = array(
            "ID" =>  $ticket->ID,
            "title" => $ticket->title,
            "body" => $ticket->body,
            "KesLokasi" => $ticket->KesLokasi,
        );
      
        http_response_code(200);
        echo json_encode($ticket_arr);
    }
      
    else{
        http_response_code(404);
        echo json_encode("Ticket not found.");
    }
 

?>
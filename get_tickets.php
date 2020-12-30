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
$ticket->guest_email = isset($_GET['guest_email']) ? $_GET['guest_email'] : die();
 
$stmt = $ticket->getTickets();
$ticketCount = $stmt->rowCount();

if($ticketCount > 0)
{
    $ticketArr = array();
    $ticketArr["ticket"] = array();

    

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $e = array(
            "ID" => $ID,
            "title" => $title,
            "body" => $body,
            "KesLokasi" => $KesLokasi,
            "ticket_date" => $ticket_date,
        );

        array_push($ticketArr["ticket"], $e);
    }

    echo json_encode($ticketArr);
}

else{
    http_response_code(404);
    echo json_encode(
        array("message" => "No ticket found.")
    );
}
 

?>
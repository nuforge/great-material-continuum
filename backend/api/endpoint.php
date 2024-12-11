<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Allow cross-origin requests
// http://localhost:8080/backend/api/endpoint.php (updated Apache DocumentRoot & directory)

header('Access-Control-Allow-Origin: http://localhost:3000');  // Allow 'http://localhost:3000'
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');  // Allow methods
header('Access-Control-Allow-Headers: Content-Type, Authorization');  // Allow specific headers

header("Content-Type: application/json");

function getUsers() {
    // Fetch all users from the 'users' table
    $db = new SQLite3(__DIR__ . '/../..' . '/database/trades.db'); // Use __DIR__ for the current script's directory
    $result = $db->query('SELECT * FROM users');
    $users = [];

    while ($row = $result->fetchArray()) {
        $users[] = $row;
    }

    echo json_encode($users); // Return users as JSON
}


function getCards($table) {
    // Fetch all cards from the passed table
    $db = new SQLite3(__DIR__ . '/../..' . '/database/trades.db'); // Use __DIR__ for the current script's directory
    $result = $db->query("SELECT * FROM $table");
    $cards = [];

    while ($row = $result->fetchArray()) {
        $cards[] = $row;
    }

    echo json_encode($cards); // Return cards as JSON
}

function getHaves() {
    getCards('haves');
}

function getWants() {
    getCards('wants');
}

function uploadCards($table) {
    // Handle the CSV upload
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['cards']) && is_array($input['cards'])) {
        $db = new SQLite3(__DIR__ . '/../..' . '/database/trades.db');
        
        foreach ($input['cards'] as $card) {
            // Insert each card into the 'haves' or 'wants' table
            $stmt = $db->prepare("INSERT INTO $table (card_name,user_id) VALUES (:card_name,:user_id)");
            $stmt->bindValue(':card_name', $card['card_name']);
            $stmt->bindValue(':user_id', $card['user_id']);
            $stmt->execute();
        }
        echo json_encode(['success' => true, 'table'=> $table, 'id' => $db->lastInsertRowID()]);
    }  else {
        echo json_encode(['message' => 'Invalid CSV data.']);
    }
}

function uploadHaves() {
    uploadCards('haves');
}

function uploadWants() {
    uploadCards('wants');
}

function deleteCard($table = 'haves') {
    // Handle the card deletion
    $input = json_decode(file_get_contents('php://input'), true);
    $cardId = $input['id'] ?? null;
    
    if (isset($cardId)) {
        $db = new SQLite3(__DIR__ . '/../..' . '/database/trades.db');
        $stmt = $db->prepare("DELETE FROM $table WHERE id = :id");
        $stmt->bindValue(':id', $cardId);
        $stmt->execute();
        
        echo json_encode(['message' => 'Card deleted successfully.']);
    } else {
        echo json_encode(['message' => 'Invalid card ID.']);
    }

}

function deleteHaveCard() {
    deleteCard('haves');
}
function deleteWantCard() {
    deleteCard('wants');
}

$routes = [
    'users' => [
        'GET' => 'getUsers',
        'POST' => 'createUser',
        'PUT' => 'updateUser',
        'DELETE' => 'deleteUser'
    ],
    'haves' => [
        'GET' => 'getHaves',
        'POST' => 'uploadHaves',
        'DELETE' => 'deleteHaveCard'
    ],
    'wants' => [
        'GET' => 'getWants',
        'POST' => 'uploadWants',
        'DELETE' => 'deleteWantCard'
    ]
];

$path = $_GET['path'] ?? null;
$method = $_SERVER['REQUEST_METHOD'];

// Check if the request is an OPTIONS request (preflight request)
if ($method == 'OPTIONS') {
    http_response_code(200); // Respond with 200 for preflight requests
    exit;
}

if ($path && isset($routes[$path][$method])) {
    $routes[$path][$method]();
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Invalid endpoint']);
}
?>

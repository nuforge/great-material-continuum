<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Allow cross-origin requests
// http://localhost:8080/backend/api/endpoint.php (updated Apache DocumentRoot & directory)

header('Access-Control-Allow-Origin: http://localhost:3000');  // Allow all domains or specify your domain like 'http://localhost:3000'
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');  // Allow methods
header('Access-Control-Allow-Headers: Content-Type, Authorization');  // Allow specific headers



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

function getCards() {
    // Fetch all users from the 'users' table
    $db = new SQLite3(__DIR__ . '/../..' . '/database/trades.db'); // Use __DIR__ for the current script's directory
    $result = $db->query('SELECT * FROM haves');
    $cards = [];

    while ($row = $result->fetchArray()) {
        $cards[] = $row;
    }

    echo json_encode($cards); // Return users as JSON
}

function uploadCards() {
    // Handle the CSV upload
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['cards']) && is_array($input['cards'])) {
        $db = new SQLite3(__DIR__ . '/../..' . '/database/trades.db');
        
        foreach ($input['cards'] as $card) {
            // Insert each card into the 'haves' or 'wants' table
            $stmt = $db->prepare('INSERT INTO haves (card_name,user_id) VALUES (:card_name,:user_id)');
            $stmt->bindValue(':card_name', $card['card_name']);
            $stmt->bindValue(':user_id', $card['user_id']);
            $stmt->execute();
        }
        
        echo json_encode(['message' => 'Cards uploaded successfully.']);
    }  else {
        echo json_encode(['message' => 'Invalid CSV data.']);
    }
}

function deleteCard() {
    // Handle the card deletion
    $input = json_decode(file_get_contents('php://input'), true);
    $cardId = $input['id'] ?? null;
    
    if (isset($cardId)) {
        $db = new SQLite3(__DIR__ . '/../..' . '/database/trades.db');
        $stmt = $db->prepare('DELETE FROM haves WHERE id = :id');
        $stmt->bindValue(':id', $cardId);
        $stmt->execute();
        
        echo json_encode(['message' => 'Card deleted successfully.']);
    } else {
        echo json_encode(['message' => 'Invalid card ID.']);
    }

}

$routes = [
    'users' => [
        'GET' => 'getUsers',
        'POST' => 'createUser',
        'PUT' => 'updateUser',
        'DELETE' => 'deleteUser'
    ],
    'cards' => [
        'GET' => 'getCards',
        'POST' => 'uploadCards',
        'DELETE' => 'deleteCard'
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

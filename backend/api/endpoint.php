<?php
define('DATABASE_PATH', realpath($_SERVER['DOCUMENT_ROOT'] .  '/database/continuum.db'));
require_once '../graphtrades.php';

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
    $db = new SQLite3(DATABASE_PATH); // Use __DIR__ for the current script's directory
    
    $result = $db->query('SELECT * FROM users');
    $users = [];

    while ($row = $result->fetchArray()) {
        $users[$row['id']] = $row;
    }

    echo json_encode($users); // Return users as JSON
}

function getUserData() {
    $db = new SQLite3(DATABASE_PATH); // Use __DIR__ for the current script's directory
    $query = "
        SELECT 
            u1.id AS user_id, 
            u1.name AS user_name, 
            inv.inventory_items, 
            wish.wishlist_items
        FROM 
            users u1
        LEFT JOIN (
            SELECT 
                i.user_id, 
                GROUP_CONCAT(i.card_id || ':' || ci.card_name) AS inventory_items
            FROM 
                inventory i
            LEFT JOIN 
                cards ci ON i.card_id = ci.id
            GROUP BY 
                i.user_id
        ) inv ON u1.id = inv.user_id
        LEFT JOIN (
            SELECT 
                w.user_id, 
                GROUP_CONCAT(w.card_id || ':' || cw.card_name) AS wishlist_items
            FROM 
                wishlist w
            LEFT JOIN 
                cards cw ON w.card_id = cw.id
            GROUP BY 
                w.user_id
        ) wish ON u1.id = wish.user_id
    ";

    $result = $db->query($query);

    $users = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $userId = $row['user_id'];
        if (!isset($users[$userId])) {
            $users[$userId] = [
                'id' => $row['user_id'],
                'name' => $row['user_name'],
                'inventory' => [],
                'wishlist' => []
            ];
        }
        if ($row['inventory_items']) {
            $inventoryItems = explode(',', $row['inventory_items']);
            foreach ($inventoryItems as $item) {
                list($card_id, $card_name) = explode(':', $item);
                $users[$userId]['inventory'][] = [
                    'card_id' => $card_id,
                    'card_name' => $card_name
                ];
            }
        }
        if ($row['wishlist_items']) {
            $wishlistItems = explode(',', $row['wishlist_items']);
            foreach ($wishlistItems as $item) {
                list($card_id, $card_name) = explode(':', $item);
                $users[$userId]['wishlist'][] = [
                    'card_id' => $card_id,
                    'card_name' => $card_name
                ];
            }
        }
    }
    echo json_encode($users); // Return users as JSON
}

function getCards() {
    // Fetch all users from the 'users' table
    $db = new SQLite3(DATABASE_PATH); // Use __DIR__ for the current script's directory
    $result = $db->query('SELECT * FROM cards');
    $cards = [];

    while ($row = $result->fetchArray()) {
        $cards[$row['id']] = $row;
    }

    echo json_encode($cards); // Return users as JSON
}

function getUserCards($table) {
    // Fetch all cards from the passed table
    $db = new SQLite3(DATABASE_PATH); // Use __DIR__ for the current script's directory
    $result = $db->query("
    SELECT DISTINCT
        t.id as id,
        u1.id AS user_id, 
        u1.name AS user_name, 
        c1.id AS card_id, 
        c1.card_name AS card_name
    FROM 
        $table t
    INNER JOIN 
        users u1 ON t.user_id = u1.id
    INNER JOIN 
        cards c1 ON t.card_id = c1.id
    ");
    while ($row = $result->fetchArray()) {
        $cards[] = $row;
    }
    $cards = $cards ?? [];
    echo json_encode($cards); // Return cards as JSON
}

function getinventory() {
    getUserCards('inventory');
}

function getwishlist() {
    getUserCards('wishlist');
}

function uploadCards($table) {
    // Handle the CSV upload
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['cards']) && is_array($input['cards'])) {
        $db = new SQLite3(DATABASE_PATH);
        
        foreach ($input['cards'] as $card) {
            // Insert each card into the 'inventory' or 'wishlist' table
            $stmt = $db->prepare("INSERT INTO $table (card_id,user_id) VALUES (:card_id,:user_id)");
            $stmt->bindValue(':card_id', $card['card_id']);
            $stmt->bindValue(':user_id', $card['user_id']);
            $stmt->execute();
        }
        echo json_encode(['success' => true, 'table'=> $table, 'id' => $db->lastInsertRowID()]);
    }  else {
        echo json_encode(['message' => 'Invalid CSV data.']);
    }
}

function uploadinventory() {
    uploadCards('inventory');
}

function uploadwishlist() {
    uploadCards('wishlist');
}

function deleteCard($table = 'inventory') {
    // Handle the card deletion
    $input = json_decode(file_get_contents('php://input'), true);
    $cardId = $input['id'] ?? null;
    
    if (isset($cardId)) {
        $db = new SQLite3(DATABASE_PATH);
        $stmt = $db->prepare("DELETE FROM $table WHERE id = :id");
        $stmt->bindValue(':id', $cardId);
        $stmt->execute();
        
        echo json_encode(['message' => 'Card deleted successfully.']);
    } else {
        echo json_encode(['message' => 'Invalid card ID.']);
    }

}

function deleteHaveCard() {
    deleteCard('inventory');
}
function deleteWantCard() {
    deleteCard('wishlist');
}

function generateTradesHandler() {
    // Fetch all trades from the 'proposed_trades' table
    $db = new SQLite3(DATABASE_PATH); // Use __DIR__ for the current script's directory
    $trades = generateTrades($db); // Call the function from graphTrades.php
    if ($trades) {
        echo $trades;
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to generate trades.']);
    }
}

$routes = [
    'users' => [
        'GET' => 'getUserData',
        'POST' => 'createUser',
        'PUT' => 'updateUser',
        'DELETE' => 'deleteUser'
    ],
    'cards' => [
        'GET' => 'getCards',
    ],
    'inventory' => [
        'GET' => 'getinventory',
        'POST' => 'uploadinventory',
        'DELETE' => 'deleteHaveCard'
    ],
    'wishlist' => [
        'GET' => 'getwishlist',
        'POST' => 'uploadwishlist',
        'DELETE' => 'deleteWantCard'
    ],
    'trades' => [
        'GET' => 'generateTradesHandler',
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

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Allow cross-origin requests
// http://localhost:8080/backend/api/endpoint.php (updated Apache DocumentRoot & directory)

header('Access-Control-Allow-Origin: http://localhost:3000');  // Allow all domains or specify your domain like 'http://localhost:3000'
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');  // Allow methods
header('Access-Control-Allow-Headers: Content-Type, Authorization');  // Allow specific headers

// Check if the request is an OPTIONS request (preflight request)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200); // Respond with 200 for preflight requests
    exit;
}
if (isset($_GET['path'])) {
    $path = $_GET['path'];
    
    if ($_GET['path'] === 'users') {
        // Fetch all users from the 'users' table
        $db = new SQLite3(__DIR__ . '/../..' . '/database/trades.db'); // Use __DIR__ for the current script's directory
        $result = $db->query('SELECT * FROM users');
        $users = [];

        while ($row = $result->fetchArray()) {
            $users[] = $row;
        }

        echo json_encode($users); // Return users as JSON
    } else {
        echo json_encode(['message' => 'Invalid endpoint']);
    }
} else {
    // If the 'path' parameter is missing, return an error
    echo json_encode(['message' => 'Missing path parameter']);
}

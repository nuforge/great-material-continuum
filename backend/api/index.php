<?php
header("Content-Type: application/json");
$db = new SQLite3('database/trades.db');

// Simple router
$method = $_SERVER['REQUEST_METHOD'];
$path = $_GET['path'] ?? '';

if ($path === 'users' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $db->prepare('INSERT INTO users (name, email) VALUES (:name, :email)');
    $stmt->bindValue(':name', $data['name']);
    $stmt->bindValue(':email', $data['email']);
    $stmt->execute();
    echo json_encode(['success' => true, 'id' => $db->lastInsertRowID()]);
}

if ($path === 'haves' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $db->prepare('INSERT INTO haves (user_id, card_name) VALUES (:user_id, :card_name)');
    $stmt->bindValue(':user_id', $data['user_id']);
    $stmt->bindValue(':card_name', $data['card_name']);
    $stmt->execute();
    echo json_encode(['success' => true, 'id' => $db->lastInsertRowID()]);
}

if ($path === 'wants' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $db->prepare('INSERT INTO wants (user_id, card_name) VALUES (:user_id, :card_name)');
    $stmt->bindValue(':user_id', $data['user_id']);
    $stmt->bindValue(':card_name', $data['card_name']);
    $stmt->execute();
    echo json_encode(['success' => true, 'id' => $db->lastInsertRowID()]);
}

// Extend for other routes as needed
?>

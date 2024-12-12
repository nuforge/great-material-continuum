<?php

$db = new SQLite3(__DIR__ . '\continuum.db');

$queries = [
    "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS cards (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        card_name TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS haves (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        card_id INTEGER NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (card_id) REFERENCES cards(id)
    )",
    "CREATE TABLE IF NOT EXISTS wants (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        card_id INTEGER NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (card_id) REFERENCES cards(id)
    )",
    "CREATE TABLE IF NOT EXISTS proposed_trades (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        trade_details TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
];

foreach ($queries as $query) {
    $db->exec($query);
}


$dataFiles = ['users', 'cards', 'haves', 'wants'];

foreach ($dataFiles as $file) {
    if (!file_exists('../public/' . $file . '.csv')) {
        echo "File not found: $file.csv";
        exit();
    }
    $data[$file] = csvToJson('../public/' . $file . '.csv');
}

foreach (json_decode($data['users'], true) as $user) {
    // Insert each card into the 'haves' or 'wants' table
    $stmt = $db->prepare("INSERT INTO users (name,email) VALUES (:name,:email)");
    $stmt->bindValue(':name', $user['name']);
    $stmt->bindValue(':email', $user['email']);
    $stmt->execute();
}
foreach (json_decode($data['cards'], true) as $card) {
    // Insert each card into the 'haves' or 'wants' table
    $stmt = $db->prepare("INSERT INTO cards (id,card_name) VALUES (:id,:card_name)");
    $stmt->bindValue(':id', $card['id']);
    $stmt->bindValue(':card_name', $card['card_name']);
    $stmt->execute();
}

foreach (json_decode($data['haves'], true) as $have) {
    // Insert each card into the 'haves' or 'wants' table
    $stmt = $db->prepare("INSERT INTO haves (card_id,user_id) VALUES (:card_id,:user_id)");
    $stmt->bindValue(':card_id', $have['card_id']);
    $stmt->bindValue(':user_id', $have['user_id']);
    $stmt->execute();
}
foreach (json_decode($data['wants'], true) as $have) {
    // Insert each card into the 'haves' or 'wants' table
    $stmt = $db->prepare("INSERT INTO wants (card_id,user_id) VALUES (:card_id,:user_id)");
    $stmt->bindValue(':card_id', $have['card_id']);
    $stmt->bindValue(':user_id', $have['user_id']);
    $stmt->execute();
}
function csvToJson($filePath) {
    // Check if file exists and is readable
    if (!file_exists($filePath) || !is_readable($filePath)) {
        return json_encode(['error' => 'File not found or not readable']);
    }

    $data = [];
    
    // Open the CSV file
    if (($handle = fopen($filePath, 'r')) !== false) {
        // Get the headers (first row)
        $headers = fgetcsv($handle);

        if ($headers === false) {
            return json_encode(['error' => 'Unable to read the file headers']);
        }

        // Process each subsequent row
        while (($row = fgetcsv($handle)) !== false) {
            $data[] = array_combine($headers, $row);
        }

        fclose($handle);
    } else {
        return json_encode(['error' => 'Unable to open file']);
    }
    // Return JSON-encoded data
    return json_encode($data);
}

echo "Database initialized successfully!";
?>

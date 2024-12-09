<?php
// Connect to the SQLite database
$db = new SQLite3(__DIR__ . '/trades.db');

// Example data to insert (replace with actual data from a form or API)
$name = 'John Doe';
$email = 'john.doe@example.com';

// Prepare the SQL query using placeholders for the parameters
$query = "INSERT INTO users (name, email) VALUES (:name, :email)";

// Prepare the statement
$stmt = $db->prepare($query);

// Bind the actual values to the placeholders
$stmt->bindValue(':name', $name, SQLITE3_TEXT);
$stmt->bindValue(':email', $email, SQLITE3_TEXT);

// Execute the query
if ($stmt->execute()) {
    echo "User added successfully!";
} else {
    echo "Error adding user: " . $db->lastErrorMsg();
}

// Close the database connection
$db->close();
?>

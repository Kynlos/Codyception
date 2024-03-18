<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit();
}

// Database connection
$db_file = realpath(dirname(__FILE__) . '/users.db');

try {
    $conn = new PDO("sqlite:$db_file");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the shared_commands table if it doesn't exist
    $conn->exec("CREATE TABLE IF NOT EXISTS shared_commands (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        json_data TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");

    // Get the entry ID and JSON data from the AJAX request
    $entry_id = $_POST['entry_id'];
    $json_data = $_POST['json_data'];

    // Insert the shared command into the shared_commands table
    $stmt = $conn->prepare("INSERT INTO shared_commands (user_id, json_data) VALUES (:user_id, :json_data)");
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':json_data', $json_data, PDO::PARAM_STR);
    $stmt->execute();

    http_response_code(200);
} catch (PDOException $e) {
    http_response_code(500);
    echo "Database error: " . $e->getMessage();
}

// Close the database connection
$conn = null;
?>

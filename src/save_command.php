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

    // Get the JSON data from the AJAX request
    $json_data = $_POST['json_data'];

    // Generate a unique ID
    $unique_id = generateUniqueID();

    // Create the user_json table if it doesn't exist
    $create_table_sql = "CREATE TABLE IF NOT EXISTS user_json (
                            id INTEGER PRIMARY KEY AUTOINCREMENT,
                            user_id INTEGER,
                            json_id TEXT,
                            json_data TEXT
                        )";
    $conn->exec($create_table_sql);

    // Insert JSON data into the user_json table
    $insert_sql = "INSERT INTO user_json (user_id, json_id, json_data)
                   VALUES (:user_id, :json_id, :json_data)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':json_id', $unique_id, PDO::PARAM_STR);
    $stmt->bindParam(':json_data', $json_data, PDO::PARAM_STR);
    $stmt->execute();

    http_response_code(200);
} catch (PDOException $e) {
    http_response_code(500);
    echo "Database error: " . $e->getMessage();
}

// Close the database connection
$conn = null;

// Function to generate a unique ID
function generateUniqueID() {
    return uniqid(); // Using PHP's built-in function to generate a unique ID
}
?>

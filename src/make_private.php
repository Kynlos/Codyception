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

    // Get the entry ID from the AJAX request
    $entry_id = $_POST['entry_id'];

    // Delete the shared command from the shared_commands table
    $stmt = $conn->prepare("DELETE FROM shared_commands WHERE id = :entry_id AND user_id = :user_id");
    $stmt->bindParam(':entry_id', $entry_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    http_response_code(200);
} catch (PDOException $e) {
    http_response_code(500);
    echo "Database error: " . $e->getMessage();
}

// Close the database connection
$conn = null;
?>

<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: register.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['entry_id'])) {
    // Database connection
    $db_file = realpath(dirname(__FILE__) . '/users.db');

    try {
        $conn = new PDO("sqlite:$db_file");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL statement to delete the entry
        $stmt = $conn->prepare("DELETE FROM user_json WHERE id = :entry_id AND user_id = :user_id");
        $stmt->bindParam(':entry_id', $_POST['entry_id'], PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        // Check if the deletion was successful
        if ($stmt->rowCount() > 0) {
            echo "Entry deleted successfully";
        } else {
            echo "Entry not found or you don't have permission to delete it";
        }

    } catch (PDOException $e) {
        // Handle database connection error
        echo "Database error: " . $e->getMessage();
    }

    // Close the database connection
    $conn = null;
} else {
    echo "Invalid request";
}
?>

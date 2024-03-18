<?php
// Define the path to the error log file
$error_log_file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'error.log';

// Check if the error log file exists, and if not, attempt to create it
if (!file_exists($error_log_file)) {
    // Attempt to create the error log file
    if (!touch($error_log_file)) {
        // Failed to create the error log file
        die('Failed to create error log file. Please check file permissions.');
    }
}

// Get the error message from the POST request
$error_message = isset($_POST['error']) ? $_POST['error'] : '';

// Log the error message to the error log file
if (!empty($error_message)) {
    // Add a timestamp to the error message
    $log_message = '[' . date('Y-m-d H:i:s') . '] ' . $error_message . PHP_EOL;
    
    // Write the error message to the error log file (append mode)
    file_put_contents($error_log_file, $log_message, FILE_APPEND);

    // Send a response back to the client
    echo 'Error logged successfully.';
} else {
    // If the error message is empty, send an error response
    http_response_code(400); // Bad Request
    echo 'Error message is empty.';
}
?>

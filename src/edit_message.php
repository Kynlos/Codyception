<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: register.php");
    exit();
}

// Database connection
$db_file = realpath(dirname(__FILE__) . '/users.db');

try {
    $conn = new PDO("sqlite:$db_file");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Update the message in the database
        $message_id = $_POST['message_id'];
        $message = $_POST['message'];
        $sql = "UPDATE messages SET message = :message WHERE id = :message_id AND user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        $stmt->bindParam(':message_id', $message_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        // Redirect back to user messages page
        header("Location: user_messages.php");
        exit();
    } else {
        // Retrieve the message from the database
        $message_id = $_GET['id'];
        $sql = "SELECT message FROM messages WHERE id = :message_id AND user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':message_id', $message_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $message = $stmt->fetchColumn();
    }
} catch (PDOException $e) {
    // Handle database connection error
    $error = "Database error: " . $e->getMessage();
}

// Close the database connection
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Message</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="index.php" class="text-xl font-bold text-gray-800">Codyception</a>
                </div>
                <div class="flex items-center">
                    <a href="command_builder.php" class="mx-2 text-gray-600 hover:text-gray-800">Commands</a>
                    <a href="profile.php" class="mx-2 text-gray-600 hover:text-gray-800">Profile</a>
                    <a href="logout.php" class="mx-2 text-gray-600 hover:text-gray-800">Logout</a>
                </div>
            </div>
        </nav>
    </header>
                    <main class="container mx-auto px-6 py-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-4">Edit Message</h2>

    <?php if (isset($error)) { ?>
        <p class="text-red-500 mb-4"><?php echo $error; ?></p>
    <?php } ?>

    <form action="edit_message.php" method="post" class="mb-8">
        <input type="hidden" name="message_id" value="<?php echo $message_id; ?>">
        <div class="mb-4">
            <label for="message" class="block text-gray-700 font-bold mb-2">Message:</label>
            <textarea id="message" name="message" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required><?php echo $message; ?></textarea>
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Message</button>
    </form>
</main>

<footer class="bg-gray-800 text-white py-4">
    <div class="container mx-auto px-6 text-center">
        &copy; <?php echo date('Y'); ?> Codyception. All rights reserved.
    </div>
</footer>

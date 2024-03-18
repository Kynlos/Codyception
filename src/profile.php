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
$conn = new SQLite3($db_file);

// Retrieve user data from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT display_name FROM users WHERE id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();

if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $user_display_name = $row["display_name"];
} else {
    // Handle error
    $error = "Error retrieving user data.";
}

$stmt->close();

// Handle profile update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_display_name = $_POST["display_name"];

    // Validate input
    if (empty($new_display_name)) {
        $error = "Display name is required.";
    } else {
        // Update user data in the database
        $sql = "UPDATE users SET display_name = :new_display_name WHERE id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':new_display_name', $new_display_name, SQLITE3_TEXT);
        $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);

        if ($stmt->execute()) {
            $success = "Display name updated successfully.";
            $user_display_name = $new_display_name;
        } else {
            $error = "Error updating display name: " . $conn->lastErrorMsg();
        }

        $stmt->close();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
        <!--Darkmode.js-->
        <script src="https://cdn.jsdelivr.net/npm/darkmode-js@1.5.7/lib/darkmode-js.min.js"></script>
    <script>
      function addDarkmodeWidget() {
        new Darkmode().showWidget();
      }
      window.addEventListener('load', addDarkmodeWidget);
    </script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="index.php" class="text-xl font-bold text-gray-800">Codyception</a>
                </div>
                <div class="flex items-center">
                <a href="dashboard.php" class="mx-2 text-gray-600 hover:text-gray-800">Dashboard</a>
                    <a href="logout.php" class="mx-2 text-gray-600 hover:text-gray-800">Logout</a>
                </div>
            </div>
        </nav>
    </header>


    <main class="container mx-auto px-6 py-8">
        <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">User Profile</h2>

            <?php if (isset($error)) { ?>
                <p class="text-red-500 mb-4"><?php echo $error; ?></p>
            <?php } ?>

            <?php if (isset($success)) { ?>
                <p class="text-green-500 mb-4"><?php echo $success; ?></p>
            <?php } ?>

            <p class="text-gray-600 mb-4">Display Name: <?php echo $user_display_name; ?></p>

            <h3 class="text-2xl font-bold text-gray-800 mb-2">Update Profile</h3>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-4">
                    <label for="display_name" class="block text-gray-700 font-bold mb-2">Display Name</label>
                    <input type="text" id="display_name" name="display_name" value="<?php echo $user_display_name; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </main>

    <style>
    body {
        padding-bottom: 80px; /* Adjust the value to match the height of your footer */
    }
    footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        z-index: 9999; /* Ensure the footer is above other content */
    }
    .copyright {
        float: left;
    }
    .privacy-policy {
        float: right;
    }
</style>

<footer class="bg-gray-800 text-white py-4">
    <div class="container mx-auto px-6 flex justify-between items-center">
        <div class="copyright">
            &copy; <?php echo date('Y'); ?> Codyception. All rights reserved.
        </div>
        <div class="privacy-policy">
            <a href="privacy.php" class="text-gray-300 hover:text-white">Privacy Policy</a>
        </div>
    </div>
</footer>
</body>
</html>

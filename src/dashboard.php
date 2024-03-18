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

    // Retrieve user data from the database
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT username, display_name, is_admin FROM users WHERE id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $user_name = $row["display_name"] ?? $row["username"]; // Use display_name if set, otherwise use username
        $is_admin = $row["is_admin"];
    } else {
        // Handle error
        $error = "Error retrieving user data.";
    }

    // If the user is an admin, fetch count of total registered users
    if ($is_admin == 1) {
        $sql_total_users = "SELECT COUNT(*) as total_users FROM users";
        $stmt_total_users = $conn->prepare($sql_total_users);
        $stmt_total_users->execute();
        $row_total_users = $stmt_total_users->fetch(PDO::FETCH_ASSOC);
        $total_users = $row_total_users['total_users'];
    }
// Function to remove duplicate entries
function removeDuplicates($conn)
{
    try {
        $sql = "DELETE FROM shared_commands WHERE ROWID NOT IN (SELECT MIN(ROWID) FROM shared_commands GROUP BY json_data)";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        // Handle error
        return false;
    }
}

// Check if the button to remove duplicates is clicked
if (isset($_POST['remove_duplicates'])) {
    if (removeDuplicates($conn)) {
        // Reload the page to reflect changes
        header("Location: admin.php");
        exit();
    } else {
        $error = "Error removing duplicate entries.";
    }
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
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--Darkmode.js-->
    <script src="https://cdn.jsdelivr.net/npm/darkmode-js@1.5.7/lib/darkmode-js.min.js"></script>
    <script>
      function addDarkmodeWidget() {
        new Darkmode().showWidget();
      }
      window.addEventListener('load', addDarkmodeWidget);
    </script>

    <style>
              header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background-color: white; /* Ensure the navbar has a background color */
        z-index: 20; /* Ensure the header has a higher z-index than the social links bar */
    }
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center">
                <a href="index.php" class="text-xl font-bold text-gray-800 mr-4">Codyception</a>
            </div>
            <div class="flex items-center">
                <!--<a href="https://donate.stripe.com/aEU28ja9qeCRc6ceUU" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition-colors duration-300 mr-4">
                    <i class="fas fa-heart mr-2"></i> Support The Project
                </a>-->
                <a href="dashboard.php" class="mx-2 text-gray-600 hover:text-gray-800 font-bold"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                        <a href="command_builder.php" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-plus-square"></i> Create</a>
                        <a href="public.php" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-folder-open"></i> Browse</a>
                        <a href="https://marketplace.visualstudio.com/items?itemName=Kynlo.codyception" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-puzzle-piece"></i> Plugin</a>
                        <a href="#" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-question-circle"></i> Help</a>
                        <a href="logout.php" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </nav>
    </header>



    <main class="container mx-auto px-6 py-8 pt-20">
    <h2 class="text-3xl font-bold text-gray-800 mb-4">Welcome, <?php echo $user_name; ?>!</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Navigation:</h3>
            <ul class="space-y-4">
                <li>
                    <a href="profile.php" class="flex items-center hover:bg-gray-100 rounded-md transition-colors duration-300 py-4 px-4">
                        <i class="fas fa-user mr-2"></i>
                        <span>View Profile</span>
                    </a>
                </li>
                <li>
                    <a href="user_messages.php" class="flex items-center hover:bg-gray-100 rounded-md transition-colors duration-300 py-4 px-4">
                        <i class="fas fa-list-alt mr-2"></i>
                        <span>View My Commands</span>
                    </a>
                </li>
                <li>
                    <a href="command_builder.php" class="flex items-center hover:bg-gray-100 rounded-md transition-colors duration-300 py-4 px-4">
                        <i class="fas fa-terminal mr-2"></i>
                        <span>Command Builder</span>
                    </a>
                </li>
                <li>
                    <a href="public.php" class="flex items-center hover:bg-gray-100 rounded-md transition-colors duration-300 py-4 px-4">
                        <i class="fas fa-globe mr-2"></i>
                        <span>Browse Public Commands</span>
                    </a>
                </li>
                <li>
                    <a href="commands.php" target="_blank" class="flex items-center hover:bg-gray-100 rounded-md transition-colors duration-300 py-4 px-4">
                        <i class="fas fa-eye mr-2"></i>
                        <span>Command Viewer</span>
                    </a>
                </li>
            </ul>
        </div>


        <?php

        // Count the entries in Cody.json
        $cody_json = file_get_contents('Cody.json');
        $cody_array = json_decode($cody_json, true);

        $commands = [];

        foreach ($cody_array['commands'] as $command => $data) {
        $commands[] = $command;
        }

        $total_commands = count(array_unique($commands));


        // Count the entries in the database table
        $db = new SQLite3('users.db');
        $result = $db->query('SELECT COUNT(*) FROM shared_commands');
        $row = $result->fetchArray(SQLITE3_NUM);
        $total_shared_commands = $row[0];
        $db->close();

        ?>

        <?php if ($is_admin == 1): ?>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Codyception Data:</h3>
            <ul class="space-y-4">
                <li>
                    <p class="text-xl font-semibold text-gray-800">Total Commands: <?php echo $total_commands; ?></p>
                </li>
                <li>
                    <p class="text-xl font-semibold text-gray-800">Total Users: <?php echo $total_users; ?></p>
                </li>
                <li>
                    <p class="text-xl font-semibold text-gray-800">Total Shared Commands: <?php echo $total_shared_commands; ?></p>
                </li>
            </ul>
        </div>
        <?php endif; ?>

        <?php if ($is_admin == 1): ?>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Admin Information:</h3>
            <ul class="space-y-4">
                <li>
                    <p class="text-xl font-semibold text-gray-800">Total Users: <?php echo $total_users; ?></p>
                </li>
            </ul>
            <!-- Button to Remove Duplicate Entries -->
        <form method="post" class="mb-4">
            <button id="remove_duplicates" type="submit" name="remove_duplicates" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">Remove Duplicate Entries</button>
        </form>
        </div>
        <?php endif; ?>
    </div>
</main>

<script>
    document.getElementById("remove_duplicates").addEventListener("click", function(event) {
        event.preventDefault(); // Prevent default form submission
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "<?php echo $_SERVER['PHP_SELF']; ?>", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status == 200) {
                // Refresh the page after removing duplicates
                location.reload();
            }
        };
        xhr.send("remove_duplicates=true");
    });
</script>



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

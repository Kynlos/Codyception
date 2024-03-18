<?php

//-----DISCORD LOGIN NOT CURRENTLY IN USE-----
//-----DISCORD LOGIN NOT CURRENTLY IN USE-----
//-----DISCORD LOGIN NOT CURRENTLY IN USE-----
//-----DISCORD LOGIN NOT CURRENTLY IN USE-----
//-----DISCORD LOGIN NOT CURRENTLY IN USE-----

// Start session
session_start();

// Include Discord OAuth configuration
$discord_client_id = 'REMOVED';
$discord_client_secret = 'REMOVED';
$discord_redirect_uri = 'https://www.codyception.com';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: command_builder.php");
    exit();
}

// Handle Discord login callback
if (isset($_GET['code'])) {
    // Exchange the authorization code for an access token
    $token_url = 'https://discord.com/api/oauth2/token';
    $data = array(
        'client_id' => $discord_client_id,
        'client_secret' => $discord_client_secret,
        'grant_type' => 'authorization_code',
        'code' => $_GET['code'],
        'redirect_uri' => $discord_redirect_uri,
        'scope' => 'identify email' // Adjust scopes as needed
    );

    $options = array(
        'http' => array(
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ),
    );

    $context = stream_context_create($options);
    $response = file_get_contents($token_url, false, $context);

    if ($response !== FALSE) {
        // Debugging: Log token response
        error_log("Token Response: " . $response);

        $token_data = json_decode($response, true);

        // Get user information using the access token
        $user_url = 'https://discord.com/api/users/@me';
        $options['http']['header'] = 'Authorization: Bearer ' . $token_data['access_token'];
        $context = stream_context_create($options);
        $user_response = file_get_contents($user_url, false, $context);

        if ($user_response !== FALSE) {
            // Debugging: Log user response
            error_log("User Response: " . $user_response);

            $user_data = json_decode($user_response, true);

            // Extract relevant user details
            $discord_user_id = $user_data['id'];
            $discord_username = $user_data['username'];
            $discord_email = $user_data['email']; // Check if email is available

            // Insert user into the database (example)
            $db_file = realpath(dirname(__FILE__) . '/users.db');
            $conn = new SQLite3($db_file);

            // Ensure table structure includes necessary columns
            $conn->exec("CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY,
                discord_user_id TEXT,
                username TEXT,
                email TEXT
            )");

            // Insert or update user data
            $stmt = $conn->prepare('INSERT OR REPLACE INTO users (discord_user_id, username, email) VALUES (:discord_user_id, :username, :email)');
            $stmt->bindValue(':discord_user_id', $discord_user_id, SQLITE3_TEXT);
            $stmt->bindValue(':username', $discord_username, SQLITE3_TEXT);
            $stmt->bindValue(':email', $discord_email, SQLITE3_TEXT);
            $stmt->execute();

            // Store user ID in session and redirect to dashboard or desired page
            $_SESSION['user_id'] = $discord_user_id;
            header("Location: command_builder.php");
            exit();
        } else {
            // Handle error fetching user information
            error_log("Failed to fetch user information from Discord.");
            echo "Failed to fetch user information from Discord.";
            exit();
        }
    } else {
        // Handle error exchanging authorization code for access token
        error_log("Failed to exchange authorization code for access token with Discord.");
        echo "Failed to exchange authorization code for access token with Discord.";
        exit();
    }
}

// Include GitHub OAuth configuration
$github_client_id = 'REMOVED';
$github_client_secret = 'REMOVED';
$github_redirect_uri = 'https://www.codyception.com/github_callback.php';

// Database connection
$db_file = realpath(dirname(__FILE__) . '/users.db');
$conn = new SQLite3($db_file);

// Check if the database connection was successful
if (!$conn) {
    // Log error and display message
    error_log("Failed to connect to the database: " . $conn->lastErrorMsg());
    echo "Failed to connect to the database. Please check the server logs for more information.";
    exit();
}

// Handle registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Your existing registration logic goes here
}

// Close database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Register</title>
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
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

<header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="index.php" class="text-xl font-bold text-gray-800">Codyception</a>
                </div>
                <div class="flex items-center">
                    <a href="index.php" class="mx-2 text-gray-600 hover:text-gray-800">Home</a>
                </div>
            </div>
        </nav>
    </header>

</div>
    <!-- Add GitHub Login button -->
    <main class="container mx-auto px-6 py-8">
        <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
            <!-- Your existing registration form -->

            <!-- GitHub Login button -->
<!-- GitHub Login button -->
<div class="flex flex-col items-center space-y-4">
    <div>
        <a href="https://github.com/login/oauth/authorize?client_id=<?php echo $github_client_id; ?>&redirect_uri=<?php echo urlencode($github_redirect_uri); ?>" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition-colors duration-300 focus:outline-none focus:shadow-outline flex items-center justify-center">
            <i class="fab fa-github mr-2"></i> Sign in with GitHub
        </a>
    </div>
<style>
.disabled {
    pointer-events: none; /* Disable clicking */
    opacity: 0.5; /* Reduce opacity to make it appear greyed out */
    cursor: not-allowed; /* Change cursor to indicate it's not clickable */
}

    </style>
    <!--<div>
    <a href="#" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-md shadow-md transition-colors duration-300 focus:outline-none focus:shadow-outline flex items-center justify-center disabled">
        <i class="fab fa-discord mr-2"></i> Login with Discord
    </a>
</div>-->

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
</div>



</body>
</html>

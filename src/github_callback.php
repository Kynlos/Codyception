<?php
session_start();

// Include GitHub OAuth configuration
$github_client_id = 'REMOVED';
$github_client_secret = 'REMOVED';
$github_redirect_uri = 'https://www.codyception.com/codylogin/github_callback.php';

// Database connection
$db_file = realpath(dirname(__FILE__) . '/users.db');
$conn = new SQLite3($db_file);

// Check if the github_id column exists in the users table
$sql = "PRAGMA table_info(users)";
$result = $conn->query($sql);
$github_id_exists = false;

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    if ($row['name'] === 'github_id') {
        $github_id_exists = true;
        break;
    }
}

// If the github_id column doesn't exist, create it
if (!$github_id_exists) {
    $sql = "ALTER TABLE users ADD COLUMN github_id INTEGER";
    $conn->exec($sql);
}

// Retrieve the authorization code from the query parameters
$code = $_GET['code'] ?? null;

if (!$code) {
    die("Error: Authorization code not found.");
}

// Exchange the authorization code for an access token
$url = 'https://github.com/login/oauth/access_token';
$data = array(
    'client_id' => $github_client_id,
    'client_secret' => $github_client_secret,
    'code' => $code,
    'redirect_uri' => $github_redirect_uri
);

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === false) {
    die("Error: Failed to obtain access token from GitHub.");
}

// Parse the access token from the response
parse_str($result, $token);

if (!isset($token['access_token'])) {
    die("Error: Failed to retrieve access token from GitHub's response.");
}

$access_token = $token['access_token'];

// Use the access token to retrieve user information from GitHub API
$url = 'https://api.github.com/user';
$options = array(
    'http' => array(
        'header' => "Authorization: token $access_token\r\nUser-Agent: PHP"
    )
);

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === false) {
    die("Error: Failed to retrieve user information from GitHub.");
}

$user_data = json_decode($result, true);

if (!$user_data || !isset($user_data['id'])) {
    die("Error: Failed to parse user information from GitHub's response.");
}

// Check if the user already exists in the database
$sql = "SELECT * FROM users WHERE github_id = :github_id";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':github_id', $user_data['id'], SQLITE3_INTEGER);
$result = $stmt->execute();
$row = $result->fetchArray(SQLITE3_ASSOC);

if ($row) {
    // User already exists, log them in
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['is_admin'] = $row['is_admin'];
} else {
    // User doesn't exist, create a new account
    $username = $user_data['login'];
    $email = isset($user_data['email']) ? $user_data['email'] : 'example@example.com'; // Set a default email or handle this case accordingly
    $github_id = $user_data['id'];

    $sql = "INSERT INTO users (username, email, github_id) VALUES (:username, :email, :github_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':github_id', $github_id, SQLITE3_INTEGER);
    $result = $stmt->execute();

    if ($result) {
        // Successfully inserted into the database
        $_SESSION['user_id'] = $conn->lastInsertRowID();
        $_SESSION['username'] = $username;
        $_SESSION['is_admin'] = 0;
    } else {
        // Failed to insert into the database
        die("Error: Failed to insert user into the database.");
    }
}

// Close database connection
$conn->close();

// Redirect the user to the dashboard
header("Location: dashboard.php");
exit();
?>

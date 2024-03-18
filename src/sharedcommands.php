<?php
// Start session
session_start();

// Database connection
$db_file = realpath(dirname(__FILE__) . '/users.db');

try {
    $conn = new PDO("sqlite:$db_file");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve shared commands and user information from the database
    $sql = "SELECT u.username, u.display_name, sc.json_data FROM shared_commands sc JOIN users u ON sc.user_id = u.id ORDER BY sc.id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $shared_commands = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle database connection error
    $error = "Database error: " . $e->getMessage();
}

// Close the database connection
$conn = null;

// Pagination
$items_per_page = 10;
$total_items = count($shared_commands);
$total_pages = ceil($total_items / $items_per_page);

if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $current_page = (int)$_GET['page'];
} else {
    $current_page = 1;
}

$offset = ($current_page - 1) * $items_per_page;
$paginated_commands = array_slice($shared_commands, $offset, $items_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/styles/default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/highlight.min.js"></script>
    <script>
    hljs.initHighlightingOnLoad();
    </script>
    <style>
        .code-container {
            position: relative;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            overflow: hidden;
            background-color: #f8f9fa;
            margin-bottom: 1rem;
        }

        .button-container {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            display: flex;
            gap: 0.5rem;
        }

        .copy-button, .save-button {
            background-color: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 0.25rem;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            cursor: pointer;
        }

        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            margin-bottom: 0;
            padding: 1rem;
        }

        .save-button:hover::after {
    content: "Login to Save";
    position: absolute;
    top: calc(100% + 5px);
    left: 50%;
    transform: translateX(-50%);
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
}

    </style>
    
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
<header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="index.php" class="text-xl font-bold text-gray-800">Codyception</a>
                </div>
                <div class="flex items-center">
                <!--<a href="https://donate.stripe.com/aEU28ja9qeCRc6ceUU" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition-colors duration-300 mr-4">
                    <i class="fas fa-heart mr-2"></i> Support The Project
                </a>-->
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a href="register.php" class="mx-2 text-gray-600 hover:text-gray-800">Login With Github</a>
                    <?php else: ?>
                        <a href="dashboard.php" class="mx-2 text-gray-600 hover:text-gray-800">Dashboard</a>
                        <a href="logout.php" class="mx-2 text-gray-600 hover:text-gray-800">Logout</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>



    <!-- Main Content -->
    <main class="container mx-auto px-6 py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Shared Commands</h2>

        <div class="mb-4">
            <input type="text" id="searchInput" class="w-full border border-gray-400 rounded px-3 py-2" placeholder="Search by username or text in command">
        </div>

        <?php if (isset($error)) { ?>
            <p class="text-red-500 mb-4"><?php echo $error; ?></p>
        <?php } ?>

        <div id="commandsContainer">
            <?php if (!empty($paginated_commands)) { ?>
                <?php foreach ($paginated_commands as $command) {
                    $decoded_command = json_decode($command['json_data'], true);
                    unset($decoded_command['id']);
                ?>
                    <div class="command-item">
                        <p class="font-bold mb-2"><?php echo $command['display_name'] ? $command['display_name'] : $command['username']; ?></p>
                        <div class="code-container">
                            <div class="button-container">
                                <?php if (!isset($_SESSION['user_id'])) { ?>
                                    <button class="copy-button" onclick="copyCode(this)">Copy Code</button>
                                    <button class="save-button" disabled>Save Command</button>
                                <?php } else { ?>
                                    <button class="copy-button" onclick="copyCode(this)">Copy Code</button>
                                    <button class="save-button" onclick="saveCommand(this)">Save Command</button>
                                <?php } ?>
                            </div>
                            <pre><code><?php echo htmlspecialchars(json_encode($decoded_command, JSON_PRETTY_PRINT)); ?></code></pre>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <!-- Display no shared commands message as before -->
            <?php } ?>
        </div>

        <?php if ($total_pages > 1) { ?>
            <div class="flex justify-center mt-4">
                <div class="flex">
                    <?php if ($current_page > 1) { ?>
                        <a href="?page=<?php echo $current_page - 1; ?>" class="mx-1 px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Previous</a>
                    <?php } ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                        <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $current_page) ? 'mx-1 px-3 py-2 bg-gray-600 text-white rounded-md' : 'mx-1 px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300'; ?>"><?php echo $i; ?></a>
                    <?php } ?>
                    <?php if ($current_page < $total_pages) { ?>
                        <a href="?page=<?php echo $current_page + 1; ?>" class="mx-1 px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Next</a>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
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
</style>
    <footer class="bg-gray-800 text-white py-4">
  <div class="container mx-auto px-6 text-center">
    &copy; <?php echo date('Y'); ?> Codyception. All rights reserved.
  </div>
</footer>


    <script>
        function copyCode(button) {
            var codeBlock = button.parentElement.nextElementSibling.querySelector('code');
            var range = document.createRange();
            range.selectNode(codeBlock);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            document.execCommand('copy');
            window.getSelection().removeAllRanges();
            button.textContent = 'Copied!';
            setTimeout(function() {
                button.textContent = 'Copy Code';
            }, 1000);
        }

        function saveCommand(button) {
            // Get the JSON data from the code block
            var codeBlock = button.parentElement.nextElementSibling.querySelector('code');
            var jsonData = codeBlock.textContent;

            // Send an AJAX request to save the command
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "save_command.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    alert("Command saved successfully!");
                } else if (xhr.readyState === XMLHttpRequest.DONE) {
                    alert("Error saving command: " + xhr.statusText);
                }
            };
            xhr.send("json_data=" + encodeURIComponent(jsonData));
        }

        var searchInput = document.getElementById('searchInput');
        var commandsContainer = document.getElementById('commandsContainer');
        var commandItems = commandsContainer.getElementsByClassName('command-item');

        searchInput.addEventListener('input', function() {
            var searchText = searchInput.value.toLowerCase();

            for (var i = 0; i < commandItems.length; i++) {
                var commandItem = commandItems[i];
                var username = commandItem.querySelector('p').textContent.toLowerCase();
                var commandText = commandItem.querySelector('pre').textContent.toLowerCase();

                if (username.includes(searchText) || commandText.includes(searchText)) {
                    commandItem.style.display = 'block';
                } else {
                    commandItem.style.display = 'none';
                }
            }
        });
    </script>


</body>
</html>

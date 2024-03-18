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

    // Retrieve entries with both ID and JSON data
    $stmt = $conn->prepare("SELECT id, json_data FROM user_json WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Create an associative array with ID as key and JSON data as value
    $json_entries = [];
    foreach ($entries as $entry) {
        $json_entries[$entry['id']] = json_decode($entry['json_data'], true);
    }

} catch (PDOException $e) {
    // Handle database connection error
    $error = "Database error: " . $e->getMessage();
}

// Close the database connection
$conn = null;

$entries_per_page = 10; // You can adjust this value according to your requirements
$total_entries = count($json_entries);
$total_pages = ceil($total_entries / $entries_per_page);
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1; // Determine the current page
$start_index = ($current_page - 1) * $entries_per_page;
$json_entries = array_slice($json_entries, $start_index, $entries_per_page, true);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Commands</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/styles/default.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/highlight.min.js"></script>
<script>
hljs.initHighlightingOnLoad();
</script>
        <!--Darkmode.js-->
        <script src="https://cdn.jsdelivr.net/npm/darkmode-js@1.5.7/lib/darkmode-js.min.js"></script>
    <script>
      function addDarkmodeWidget() {
        new Darkmode().showWidget();
      }
      window.addEventListener('load', addDarkmodeWidget);
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


              /* Style for the fixed navbar */
      header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background-color: white; /* Ensure the navbar has a background color */
        z-index: 20; /* Ensure the header has a higher z-index than the social links bar */
    }

    .code-container {
            position: relative;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            overflow: hidden;
            background-color: #f8f9fa;
            margin-bottom: 1rem;
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
                <a href="dashboard.php" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                        <a href="command_builder.php" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-plus-square"></i> Create</a>
                        <a href="public.php" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-folder-open"></i> Browse</a>
                        <a href="https://marketplace.visualstudio.com/items?itemName=Kynlo.codyception" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-puzzle-piece"></i> Plugin</a>
                        <a href="#" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-question-circle"></i> Help</a>
                        <a href="logout.php" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </nav>
    </header>







<main class="container mx-auto px-6 py-8 pt-20">
    <h2 class="text-3xl font-bold text-gray-800 mb-4">My Commands (JSON Format)</h2>

    <?php if (isset($error)) { ?>
        <p class="text-red-500 mb-4"><?php echo $error; ?></p>
    <?php } ?>

    <?php foreach ($json_entries as $id => $entry) { ?>
    <div class="bg-gray-200 p-4 rounded-lg mb-4 relative">
        <input type="hidden" value="<?php echo $id; ?>">
        <button class="absolute top-0 right-0 mr-2 px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:bg-blue-600" onclick="copyToClipboard(this)">
            Copy
        </button>

        <?php if ($entry['is_shared']) { ?>
            <button class="absolute top-0 right-14 mr-2 px-4 py-1 bg-red-500 text-white rounded hover:bg-red-600 focus:outline-none focus:bg-red-600" onclick="makePrivate(this)">
                <!--Make Private-->
                Make Public
            </button>
        <?php } else { ?>
            <button class="absolute top-0 right-14 mr-2 px-4 py-1 bg-green-500 text-white rounded hover:bg-green-600 focus:outline-none focus:bg-green-600" onclick="shareCommand(this)">
                Make Public
            </button>
        <?php } ?>

        <button class="absolute top-0 right-30 px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 focus:outline-none focus:bg-red-600" onclick="confirmDelete(this)">
            Delete
        </button>

        <code class="block whitespace-pre-wrap pt-5"><?php echo json_encode($entry, JSON_PRETTY_PRINT); ?></code>
    </div>
<?php } ?>



    <!-- Pagination -->
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

<script>
    function copyToClipboard(button) {
        var codeBlock = button.parentElement.querySelector('code');
        var tempInput = document.createElement('textarea');

        // Remove the ID from the copied JSON
        var jsonWithoutId = JSON.parse(codeBlock.textContent);
        delete jsonWithoutId.id;

        tempInput.value = JSON.stringify(jsonWithoutId, null, 2);
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        button.textContent = 'Copied!';
        setTimeout(function() {
            button.textContent = 'Copy';
        }, 1000);
    }

    function confirmDelete(button) {
        if (confirm("Are you sure you want to delete this entry?")) {
            var idInput = button.parentElement.querySelector('input[type="hidden"]');
            var entryId = idInput.value;

            // AJAX request to delete the entry
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_entry.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    console.log(xhr.responseText); // Log response from the server
                    // Assuming successful deletion, remove the entry from the DOM
                    button.parentElement.remove();
                }
            };
            xhr.send("entry_id=" + entryId);
        }
    }

    function shareCommand(button) {
        var codeBlock = button.parentElement.querySelector('code');
        var jsonData = codeBlock.textContent;
        var idInput = button.parentElement.querySelector('input[type="hidden"]');
        var entryId = idInput.value;

        // AJAX request to share the command
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "public_share.php", true); // Change to public_share.php
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    console.log(xhr.responseText); // Log response from the server
                    button.textContent = 'Make Public'; // Change button text
                    button.setAttribute('onclick', 'makePrivate(this)'); // Change button onclick function
                } else {
                    console.error("Error sharing command:", xhr.status, xhr.statusText); // Log error
                    alert("Error sharing command. Please try again.");
                }
            }
        };
        xhr.send("entry_id=" + entryId + "&json_data=" + encodeURIComponent(jsonData));
    }

    function makePrivate(button) {
        var idInput = button.parentElement.querySelector('input[type="hidden"]');
        var entryId = idInput.value;

        // AJAX request to make the command private
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "make_private.php", true); // Create make_private.php to handle this
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    console.log(xhr.responseText); // Log response from the server
                    button.textContent = 'Make Public'; // Change button text back
                    button.setAttribute('onclick', 'shareCommand(this)'); // Change button onclick function back
                } else {
                    console.error("Error making command private:", xhr.status, xhr.statusText); // Log error
                    alert("Error making command private. Please try again.");
                }
            }
        };
        xhr.send("entry_id=" + entryId);
    }
</script>



</body>
</html>

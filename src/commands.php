<?php
// Start the session (if not already started)
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

// Function to conditionally render navigation links based on login status
function renderNavigationLinks($isLoggedIn) {
    if ($isLoggedIn) {
        echo '
        <a href="dashboard.php" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="command_builder.php" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-plus-square"></i> Create</a>
        <a href="public.php" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-folder-open"></i> Browse</a>
        <a href="https://marketplace.visualstudio.com/items?itemName=Kynlo.codyception" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-puzzle-piece"></i> Plugin</a>
        <a href="#" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-question-circle"></i> Help</a>
        <a href="logout.php" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-sign-out-alt"></i> Logout</a>
        ';
    } else {
        echo '
            <a href="login.php" class="mx-2 text-gray-600 hover:text-gray-800">Login</a>
            <a href="register.php" class="mx-2 text-gray-600 hover:text-gray-800">Register</a>
        ';
    }
}

// Function to handle saving the command to the user's account
function saveToAccount($key) {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect the user to the login page if not logged in
        header("Location: login.php");
        exit; // Stop further execution
    }

    // Implement your saving logic here


    // Example: Saving the command key to the user's account
    $userId = $_SESSION['user_id'];

    echo "Command saved to account: $key";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Command Browser</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!--Darkmode.js-->
    <script src="https://cdn.jsdelivr.net/npm/darkmode-js@1.5.7/lib/darkmode-js.min.js"></script>
    <script>
        function addDarkmodeWidget() {
            new Darkmode().showWidget();
        }
        window.addEventListener('load', addDarkmodeWidget);
    </script>
    <style>
        /* Style for the fixed navbar */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: white;
            /* Ensure the navbar has a background color */
            z-index: 20;
            /* Ensure the header has a higher z-index than the social links bar */
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
                    <?php renderNavigationLinks($isLoggedIn); ?>
                </div>
            </div>
        </nav>
    </header>

    <main class="container mx-auto pt-10">
        <!-- JSON Viewer with Pagination -->
        <div class="w-full my-8 mx-auto max-w-7xl">
            <div class="search-bar bg-white p-4 rounded-md mb-4">
                <i class="fas fa-search search-icon text-gray-600"></i>
                <input type="text" id="liveSearch" class="search-input w-full border p-2 rounded-md"
                    placeholder="Search...">
            </div>

            <!-- Pagination buttons moved above the table with padding below -->
            <div id="pagination" class="flex justify-between items-center mt-4 space-x-2 mb-4"></div>

            <div class="flex justify-between items-center mb-4">
                <table class="min-w-full bg-white rounded-lg overflow-hidden">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left">Prompt Name</th>
                            <th class="py-3 px-4 text-left">Prompt</th>
                            <th class="py-3 px-4 text-left">Context</th>
                            <!-- New column for Save button -->
                            <th class="py-3 px-4 text-left">Save</th>
                        </tr>
                    </thead>
                    <tbody id="jsonTableBody">
                        <!-- Table body content goes here -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Sticky Footer -->
    <style>
        body {
            padding-bottom: 80px;
            /* Adjust the value to match the height of your footer */
        }

        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 9999;
            /* Ensure the footer is above other content */
        }
    </style>
    <footer class="bg-gray-800 text-white py-4">
        <div class="container mx-auto px-6 text-center">
            &copy; <?php echo date('Y'); ?> Codyception. All rights reserved.
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/twbs-pagination@1.4.2/dist/jquery.twbsPagination.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let jsonData;
            let pageSize = 10;
            let currentPage = 1;
            let debounceTimer;

            // Function to copy text to clipboard
            function copyToClipboard(text) {
                const dummy = document.createElement('textarea');
                document.body.appendChild(dummy);
                dummy.value = text;
                dummy.select();
                document.execCommand('copy');
                document.body.removeChild(dummy);
            }

            // Function to handle table row click and copy JSON data
            function handleRowClick(key) {
                const dataToCopy = JSON.stringify(jsonData.commands[key], null, 2);
                copyToClipboard(dataToCopy);
                alert('Copied: ' + dataToCopy); // You can replace this with a more subtle notification
            }

            // Function to handle saving the command to the user's account
            function saveToAccount(key) {
                // Call the PHP function to handle saving to the user's account
                <?php if ($isLoggedIn) { ?>
                    // If the user is logged in, call the PHP function to save to their account
                    <?php echo "saveToAccount('$key');"; ?>
                <?php } else { ?>
                    // If the user is not logged in, prompt them to log in
                    alert('Please login to save commands to your account.');
                    // You can also redirect the user to the login page if needed
                    // window.location.href = 'login.php';
                <?php } ?>
            }

            // Function to update pagination link classes
            function updatePaginationLinkClasses() {
                document.querySelectorAll('#pagination button').forEach(function (button) {
                    button.classList.add('bg-blue-500', 'text-white', 'px-4', 'py-2', 'rounded-md', 'hover:bg-blue-600');
                });
            }

            // Function to paginate JSON data and load it into the table
            function paginateAndLoad(commands, startIdx, endIdx) {
                const tableBody = document.getElementById('jsonTableBody');
                tableBody.innerHTML = '';

                // Check if commands is iterable
                if (!commands || !Array.isArray(commands) || commands.length === 0) {
                    const noResultsRow = document.createElement('tr');
                    noResultsRow.innerHTML = `<td colspan="4" class="border p-2 text-center">No results found.</td>`;
                    tableBody.appendChild(noResultsRow);
                    return;
                }

                // Populate the table with data
                for (let i = startIdx; i < endIdx && i < commands.length; i++) {
                    const [key, value] = commands[i];
                    const contextKeys = Object.keys(value.context);
                    const contextString = contextKeys.join(', ');

                    const row = document.createElement('tr');
                    row.setAttribute('data-key', key);
                    row.innerHTML = `
                        <td class="border p-2">${key}</td>
                        <td class="border p-2">${value.prompt}</td>
                        <td class="border p-2">${contextString}</td>
                        <td class="border p-2">
                            <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600" onclick="saveToAccount('${key}')">Save</button>
                        </td>
                    `;
                    row.addEventListener('click', function () { handleRowClick(key); });
                    row.addEventListener('mouseover', function () { row.classList.add('bg-gray-200'); });
                    row.addEventListener('mouseout', function () { row.classList.remove('bg-gray-200'); });
                    tableBody.appendChild(row);
                }

                // Update pagination link classes
                updatePaginationLinkClasses();
            }

            // Fetch JSON data from Cody.json and then load it into the table
            fetch('Cody.json')
                .then(response => response.json())
                .then(data => {
                    jsonData = data;
                    const commands = Object.entries(jsonData.commands);
                    const totalPages = Math.ceil(commands.length / pageSize);
                    createPaginationButtons(totalPages);
                    paginateAndLoad(commands, 0, pageSize); // Initial page size set to 10 commands
                })
                .catch(error => console.error('Error fetching Cody.json:', error));

            // Function to create pagination buttons
            function createPaginationButtons(totalPages) {
                const paginationContainer = document.getElementById('pagination');
                paginationContainer.innerHTML = '';

                // Previous button
                const prevButton = document.createElement('button');
                prevButton.textContent = 'Prev';
                prevButton.classList.add('bg-blue-500', 'text-white', 'px-4', 'py-2', 'rounded-md', 'hover:bg-blue-600');
                prevButton.addEventListener('click', function () {
                    if (currentPage > 1) {
                        currentPage--;
                        const startIdx = (currentPage - 1) * pageSize;
                        const endIdx = startIdx + pageSize;
                        paginateAndLoad(Object.entries(jsonData.commands), startIdx, endIdx);
                    }
                });
                paginationContainer.appendChild(prevButton);

                // Next button
                const nextButton = document.createElement('button');
                nextButton.textContent = 'Next';
                nextButton.classList.add('bg-blue-500', 'text-white', 'px-4', 'py-2', 'rounded-md', 'hover:bg-blue-600');
                nextButton.addEventListener('click', function () {
                    const totalPages = Math.ceil(Object.keys(jsonData.commands).length / pageSize);
                    if (currentPage < totalPages) {
                        currentPage++;
                        const startIdx = (currentPage - 1) * pageSize;
                        const endIdx = startIdx + pageSize;
                        paginateAndLoad(Object.entries(jsonData.commands), startIdx, endIdx);
                    }
                });
                paginationContainer.appendChild(nextButton);
            }

            // Function to filter commands based on the search term using regular expressions
            function filterCommands(searchTerm) {
                const regex = new RegExp(searchTerm, 'i'); // 'i' flag for case-insensitive matching
                return Object.entries(jsonData.commands).filter(([key, value]) =>
                    regex.test(value.prompt) ||
                    regex.test(value.description)
                );
            }

            // Function to handle live search with debouncing
            function handleLiveSearch() {
                const searchTerm = this.value.trim(); // Trim leading and trailing whitespace
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const filteredCommands = filterCommands(searchTerm);
                    const totalPages = Math.ceil(filteredCommands.length / pageSize);
                    createPaginationButtons(totalPages);
                    paginateAndLoad(filteredCommands, 0, pageSize);
                }, 100); // Debounce delay of 300 milliseconds
            }

            // Event listener for live search input with debounce
            document.getElementById('liveSearch').addEventListener('input', handleLiveSearch);
        });
    </script>

</body>

</html>

<?php
// Start session
session_start();

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: register.php");
    exit();
}

// Database connection
$db_file = realpath(dirname(__FILE__) . '/users.db');

try {
    $conn = new PDO("sqlite:$db_file");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve admin data and user data from the database
    $admin_id = $_SESSION['user_id'];
    $sql = "SELECT username, email FROM users WHERE id = :admin_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':admin_id', $admin_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $admin_name = $row["username"];
        $admin_email = $row["email"];
    } else {
        // Handle error
        $error = "Error retrieving admin data.";
    }

    // Retrieve list of users
    $sql = "SELECT id, username, email FROM users";
    $stmt = $conn->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle database connection error
    $error = "Database error: " . $e->getMessage();
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

// Function to save chat messages
function saveChatMessage($conn, $userId, $message) {
    $sql = "INSERT INTO chats (user_id, message) VALUES (:user_id, :message)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':message', $message);
    $stmt->execute();
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

// Check if the "new chat" button is clicked
if (isset($_POST['new_chat'])) {
    // Save the current chat messages
    // You may need to adjust this logic based on your requirements
    // Here, we assume saving chat messages is similar to when a user sends a message
    $userId = $_SESSION['user_id'];
    $message = "New chat started";
    saveChatMessage($conn, $userId, $message);

    // Redirect to the same page to start a new chat
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
    <!-- Include Prism.js -->
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.24.1/prism.min.js"></script>
    <!-- Include Prism CSS theme (adjust this based on your preference) -->
    <link href="https://cdn.jsdelivr.net/npm/prismjs@1.24.1/themes/prism.css" rel="stylesheet">
</head>

    <style>
        /* Style for code highlighting */
        .code-block {
            background-color: #f7f7f7;
            border-radius: 4px;
            padding: 8px;
            margin-bottom: 8px;
        }
    </style>
</head>

<body class="flex h-screen">

<!-- Sidebar Section 
<aside class="bg-gray-200 w-1/4 p-4">
    <h2 class="text-lg font-semibold mb-4">Saved Chats</h2>
    Display saved chats or options to save chats 
</aside>-->

<!-- Chat Section -->
<main class="flex-grow flex flex-col px-6 py-8">
    <div id="chatArea" class="flex flex-col space-y-4 flex-grow overflow-y-auto"></div>
    <textarea id="userInput" class="w-full h-24 border border-gray-300 rounded-md px-4 py-2" placeholder="Type your message..."></textarea>
    <button id="sendButton" class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded mt-2">Send</button>
</main>

<script>
    async function generateImprovedPrompt(originalPrompt) {
        const systemPrompts = [
            "You are an expert programmer highly skilled in Python, HTML, PHP, Javascript and TailwindCSS"
        ];

        const proxyUrl = 'cf_proxy.php';

        const requestBody = {
            messages: [
                { role: 'system', content: systemPrompts.join('\n') }, // Join all system prompts with newline characters
                { role: 'user', content: `${originalPrompt}` },
            ],
            max_tokens: 32000, // Set the maximum number of new tokens to generate
        };

        const response = await fetch(proxyUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(requestBody),
        });

        if (!response.ok) {
            throw new Error(`HTTP error ${response.status}`);
        }

        const result = await response.json();

        if (result.success && result.result && result.result.response) {
            return result.result.response.trim();
        } else {
            throw new Error('No response available in the response');
        }
    }

// Function to add messages to the chat interface
function addMessageToChat(message, sender, language = null) {
    const chatArea = document.getElementById('chatArea');
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('flex', 'flex-col');

    const senderClass = sender === 'user' ? 'text-right' : 'text-left';

    if (language) {
        // If language is specified, wrap the message in <code> tags with language class
        messageDiv.innerHTML = `
            <div class="${senderClass}">
                <pre class="code-block"><code class="language-${language}">${message}</code></pre>
            </div>
        `;
    } else {
        // If no language specified, treat it as regular text
        const bgColor = sender === 'user' ? 'bg-blue-500' : 'bg-gray-300';
        const textColor = sender === 'user' ? 'text-white' : 'text-gray-800';

        // Check if the message starts and ends with triple backticks to indicate code snippet
        if (message.startsWith('```') && message.endsWith('```')) {
            // Extract the code content and remove the surrounding triple backticks
            const codeContent = message.substring(3, message.length - 3).trim();
            // Wrap the code content in Markdown code block with Python syntax highlighting
            message = '```python\n' + codeContent + '\n```';
        }

        messageDiv.innerHTML = `
            <div class="${senderClass}">
                <span class="inline-block px-4 py-2 rounded-md ${bgColor} ${textColor}">${message}</span>
            </div>
        `;
    }
    
    chatArea.appendChild(messageDiv);
    chatArea.scrollTop = chatArea.scrollHeight; // Scroll to bottom

    // Highlight code using Prism.js
    Prism.highlightAll();
}

    // Function to handle user input
    function handleUserInput() {
        const userInput = document.getElementById('userInput').value;
        if (!userInput.trim()) return; // Don't send empty messages

        addMessageToChat(userInput, 'user'); // Add user message to chat
        document.getElementById('userInput').value = ''; // Clear input field

        generateImprovedPrompt(userInput)
            .then(response => {
                addMessageToChat(response, 'bot', true); // Add AI response to chat with code highlighting
            })
            .catch(error => {
                console.error('Error generating AI response:', error);
                addMessageToChat('An error occurred while generating the AI response. Please try again later.', 'bot');
            });
    }

    // Event listener for send button
    document.getElementById('sendButton').addEventListener('click', handleUserInput);

    // Event listener for Enter key press
    document.getElementById('userInput').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Prevent newline in textarea
            handleUserInput();
        }
    });
</script>

</body>
</html>

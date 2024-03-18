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

    // Retrieve user data from the session
    $user_id = $_SESSION['user_id'];

    // Check if the form data is submitted and the required fields are set
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['jsonData'])) {
        // Extract JSON data from the form
        $json_data = $_POST['jsonData'];

        // Generate a unique ID
        $unique_id = generateUniqueID();

        // Create the user_json table if it doesn't exist
        $create_table_sql = "CREATE TABLE IF NOT EXISTS user_json (
                                id INTEGER PRIMARY KEY AUTOINCREMENT,
                                user_id INTEGER,
                                json_id TEXT,
                                json_data TEXT
                            )";
        $conn->exec($create_table_sql);

        // Insert JSON data into the user_json table
        $insert_sql = "INSERT INTO user_json (user_id, json_id, json_data)
                       VALUES (:user_id, :json_id, :json_data)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':json_id', $unique_id, PDO::PARAM_STR);
        $stmt->bindParam(':json_data', $json_data, PDO::PARAM_STR);
        $stmt->execute();

    }
} catch (PDOException $e) {
    // Handle database connection error
    $error = "Database error: " . $e->getMessage();
}

// Close the database connection
$conn = null;

// Function to generate a unique ID
function generateUniqueID() {
    return uniqid(); // Using PHP's built-in function to generate a unique ID
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Command Builder</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Select2 CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!--Darkmode.js-->
    <script src="https://cdn.jsdelivr.net/npm/darkmode-js@1.5.7/lib/darkmode-js.min.js"></script>
    <script>
      function addDarkmodeWidget() {
        new Darkmode().showWidget();
      }
      window.addEventListener('load', addDarkmodeWidget);
    </script>



    <style>
        .context-button.selected {
            background-color: #4CAF50; /* Green */
            color: white;
        }

        /* Reduce space between labels and input boxes */
        label {
            margin-bottom: 0.5rem;
        }


    /* Ensure proper spacing for content below the navbars */
    body {
        padding-top: 80px; 
        padding-bottom: 20px; /* Add padding below the social links bar */
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

    /* Style for the fixed social links bar */
.bg-gray-200 {
    position: fixed;
    top: 60px; /* Adjust this value to match the height of header */
    left: 0;
    width: 100%;
    z-index: 10;
    padding-bottom: 1px; /* Add padding below the social links bar */
} 

#improvePromptButton {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

#improvePromptButton .fas {
    font-size: 1.5rem; /* Adjust the size of the icon as needed */
}

</style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal flex flex-col min-h-screen pb-16">
    <header class="bg-white shadow-md fixed top-0 w-full bg-opacity-100 z-10">
        <nav class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="index.php" class="text-xl font-bold text-gray-800">Codyception</a>
                </div>
                <div class="flex items-center">
                <a href="dashboard.php" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                        <a href="command_builder.php" class="mx-2 text-gray-600 hover:text-gray-800 font-bold"><i class="fas fa-plus-square"></i> Create</a>
                        <a href="public.php" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-folder-open"></i> Browse</a>
                        <a href="https://marketplace.visualstudio.com/items?itemName=Kynlo.codyception" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-puzzle-piece"></i> Plugin</a>
                        <a href="#" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-question-circle"></i> Help</a>
                        <a href="logout.php" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </nav>
    </header>


</body>




<!-- 3. Main Content Area -->
<main class="my-0.25 flex-grow">
    <div class="max-w-5xl mx-auto px-4">

        <!-- 4. Select Pre-made Command Dropdown -->
        <section class="my-8">
            <div class="max-w-3xl mx-auto">
                <label for="premadeCommand" class="block text-lg font-semibold text-gray-700 mb-2">Select Pre-made Command (Optional):</label>
                <div class="relative">
                    <select id="premadeCommand" name="premadeCommand" class="form-input mt-1 p-3 border border-gray-300 rounded-md w-full appearance-none bg-white bg-no-repeat pr-8 focus:outline-none focus:ring-2 focus:ring-blue-600" style="background-image: url('data:image/svg+xml;utf8,<svg fill='%236b7280' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'><path clip-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' fill-rule='evenodd'/></svg>'); background-position: right 0.5rem center; background-size: 1.5em 1.5em;">
                        <!-- Options will be dynamically populated using JavaScript -->
                    </select>
                </div>
            </div>
        </section>

        <!-- 5. Command Name -->
        <section class="my-8">
            <div class="max-w-3xl mx-auto">
                <label for="commandName" class="block text-lg font-semibold text-gray-700 mb-2">Command Name:</label>
                <input type="text" id="commandName" name="commandName" class="mt-1 p-3 border border-gray-300 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>
        </section>

        <!-- 6. Command Prompt -->
        <section class="my-8">
            <div class="max-w-3xl mx-auto">
                <label for="commandPrompt" class="block text-lg font-semibold text-gray-700 mb-2">Command Prompt (Prompt to send to Cody.):</label>
                <div class="flex">
                    <textarea id="commandPrompt" name="commandPrompt" class="mt-1 p-3 border border-gray-300 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-600" rows="4"></textarea>
                    <button id="improvePromptButton" class="ml-2 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-600" onclick="improvePrompt()">
                <i class="fas fa-robot mb-1 block"></i> 
                <span class="inline-block">Automatically Improve Prompt With AI</span>
            </button>

                </div>
                <div id="promptGenerationStatus" class="mt-2 text-gray-600 hidden">Generating improved prompt (This could take up to 30 seconds)...</div>
            </div>
        </section>


<!-- 7. Command Context Dropdown -->
<section class="my-8">
    <div class="max-w-3xl mx-auto">
        <label for="commandContext" class="block text-lg font-semibold text-gray-700 mb-2">Command Context (Click an item to select):</label>
        <div class="flex flex-wrap justify-center gap-4">
            <button id="selection" class="context-button bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-600" onclick="toggleSelection(this)"><i class="fas fa-mouse-pointer mr-2"></i> Selection</button>
            <button id="openTabs" class="context-button bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-600" onclick="toggleSelection(this)"><i class="fas fa-folder-open mr-2"></i> Open Tabs</button>
            <button id="currentDir" class="context-button bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-600" onclick="toggleSelection(this)"><i class="fas fa-folder mr-2"></i> Current Dir</button>
            <button id="currentFile" class="context-button bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-600" onclick="toggleSelection(this)"><i class="far fa-file mr-2"></i> Current File</button>
            <button id="command" class="context-button bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-600" onclick="toggleSelection(this)"><i class="fas fa-terminal mr-2"></i> Command</button>
            <button id="none" class="context-button bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-600" onclick="toggleSelection(this)"><i class="fas fa-ban mr-2"></i> None</button>
        </div>
    </div>
</section>


        <!-- 8. Command Note -->
        <section class="my-8">
            <div class="max-w-3xl mx-auto">
                <label for="commandNote" class="block text-lg font-semibold text-gray-700 mb-2">Command Note (Optional):</label>
                <div class="flex">
                    <textarea id="commandNote" name="commandNote" class="mt-1 p-3 border border-gray-300 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-600" rows="2"></textarea>
                    <button id="summarizePromptButton" class="ml-2 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-600" onclick="summarizePrompt()">
                        <i class="fas fa-file-alt mr-2"></i>
                        <span class="inline-block">Summarize Prompt</span>
                    </button>
                </div>
                <div id="summarizePromptStatus" class="mt-2 text-gray-600 hidden">Summarizing prompt, please wait...</div>
            </div>
        </section>


        <!-- 10. Buttons and Modals -->
        <section class="my-8">
            <div class="max-w-3xl mx-auto px-4 flex flex-col items-center">
            <form id="saveCommandForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="submitCommandForm(); return false;" class="w-full">
                <!-- Add hidden input field for context data -->
                <input type="hidden" id="commandContext" name="commandContext" value="">
                <!-- Include the hidden input field for JSON data -->
                <input type="hidden" id="jsonData" name="jsonData" value="">
                <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-600 mb-4 flex justify-center items-center" onclick="showSavedMessage(this);">Save Command To Account</button>
            </form>

            <script>
                function submitCommandForm() {
                    // Retrieve JSON data
                    var jsonData = document.getElementById('jsonData').value;

                    // Set the value of commandContext input field
                    document.getElementById('commandContext').value = 'someContext'; // You can replace 'someContext' with appropriate value

                    // Submit the form
                    document.getElementById('saveCommandForm').submit();
                }

                function showSavedMessage(button) {
                    // Change button text to "Saved!"
                    button.textContent = 'Saved!';

                    // After 1 second, revert button text back to "Save Command To Account"
                    setTimeout(function() {
                        button.textContent = 'Save Command To Account';
                    }, 1000);
                }
            </script>

        </section>


    </div>
</main>




<style>
    body {
        padding-bottom: 80px; /* Adjust the value to match the height of the footer */
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

function summarizePrompt() {
    const originalPrompt = document.getElementById('commandPrompt').value;

    // Check if the original prompt is empty
    if (!originalPrompt.trim()) {
        alert("Please enter a prompt before summarizing.");
        return;
    }

    // Call the PHP proxy to access the Cloudflare AI API
    generateImprovedPromptWithCloudflareAI(originalPrompt)
        .then(improvedPrompt => {
            // Define an array of XML tags to remove
            const xmlTags = ['<enhanced_prompt>', '</enhanced_prompt>'];

            // Remove the XML tags from the improved prompt
            xmlTags.forEach(tag => {
                improvedPrompt = improvedPrompt.replace(tag, '');
            });

            // Trim any leading/trailing whitespace
            improvedPrompt = improvedPrompt.trim();

            // Summarize the improved prompt
            summarizeImprovedPrompt(improvedPrompt);
        })
        .catch(error => {
            console.error('Error generating improved prompt:', error);
            alert('An error occurred while generating the improved prompt. Please try again later.');
        });
}

// Function to show or hide the waiting text based on the content of the commandNote textarea
function toggleWaitingText() {
    const commandNoteText = $('#commandNote').val().trim();
    if (commandNoteText === '') {
        $('#summarizePromptStatus').removeClass('hidden');
    } else {
        $('#summarizePromptStatus').addClass('hidden');
    }
}

// Function to summarize the prompt
async function summarizePrompt() {
    // Show the waiting text
    $('#summarizePromptStatus').removeClass('hidden');

    try {
        // Get the content of the commandPrompt textarea
        const commandPromptText = $('#commandPrompt').val().trim();

        // Send the prompt to the Summarize API
        const response = await fetch('cf_proxy.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                messages: [

                { role: 'user', content: `Create a set of tags and a short note about this LLM Prompt to be used for searching later.  Ensure you reply only in English: ${commandPromptText}` }, // Specify role as 'user' and include the prompt text

                ],
                max_new_tokens: 512,
            }),
        });

        // Parse response
        const data = await response.json();

        // Get the summarized text
        const summary = data.result.response.trim();

        // Update the commandNote textarea with the summary
        $('#commandNote').val(summary);
    } catch (error) {
        console.error('Error summarizing prompt:', error);
        alert('An error occurred while summarizing the prompt. Please try again later.');
    } finally {
        // Hide the waiting text
        $('#summarizePromptStatus').addClass('hidden');
    }
}

// Add event listener to the button to trigger the summarizePrompt function
$('#summarizePromptButton').on('click', function() {
    // Toggle waiting text visibility
    toggleWaitingText();

    // Summarize the prompt
    summarizePrompt();
});

// Add event listener to the commandNote textarea to toggle waiting text visibility when its content changes
$('#commandNote').on('input', toggleWaitingText);
</script>








    <script>
function improvePrompt() {
    const originalPrompt = document.getElementById('commandPrompt').value;
    const promptGenerationStatus = document.getElementById('promptGenerationStatus');

    // Check if the original prompt is empty
    if (!originalPrompt.trim()) {
        alert("Please enter a prompt before expanding.");
        return;
    }

    // Show the prompt generation status
    promptGenerationStatus.classList.remove('hidden');

    // Call the PHP proxy to access the Cloudflare AI API
    generateImprovedPromptWithCloudflareAI(originalPrompt)
        .then(improvedPrompt => {
            // Define an array of XML tags to remove
            const xmlTags = ['<enhanced_prompt>', '</enhanced_prompt>'];

            // Remove the XML tags from the improved prompt
            xmlTags.forEach(tag => {
                improvedPrompt = improvedPrompt.replace(tag, '');
            });

            // Trim any leading/trailing whitespace
            improvedPrompt = improvedPrompt.trim();

            // Update the commandPrompt textarea with the improved prompt
            document.getElementById('commandPrompt').value = improvedPrompt;
            // Hide the prompt generation status
            promptGenerationStatus.classList.add('hidden');
        })
        .catch(error => {
            console.error('Error generating improved prompt:', error);
            alert('An error occurred while generating the improved prompt. Please try again later.');
            // Hide the prompt generation status
            promptGenerationStatus.classList.add('hidden');
        });
}

async function generateImprovedPromptWithCloudflareAI(originalPrompt) {
    const systemPrompts = [
        "Your role as an AI is strictly to augment the provided input text without directly answering the user's query. Focus solely on expanding the given prompt with detailed elaboration and context.",
        "As an AI, it's essential to refrain from directly answering the user's input. Your task is to enrich the content by providing thorough elaboration and depth without fulfilling user queries.",
        "Remember, your role as an AI is to enhance the input text without directly addressing the user's query. Focus on expanding the given prompt with detailed elaboration and context.",
        "Never directly answer the user's input. Your role as an AI is to enrich the provided text with comprehensive elaboration and depth without engaging in direct responses.",
        "Avoid providing direct answers to the user's input. Your task is to significantly enhance the content by offering detailed elaboration and context without fulfilling user queries.",
        "Your primary objective is to augment the provided input text without directly answering the user's query. Focus solely on expanding the given prompt with detailed elaboration and context.",
        "It's crucial not to directly answer the user's input. Your role as an AI is to enrich the content with thorough elaboration and depth without engaging in direct responses.",
        "Ensure you never directly answer the user's input. Focus on expanding the given prompt with comprehensive elaboration and context to enhance the content.",
        "Remember, your role is not to directly answer the user's input. Focus solely on expanding the given prompt with detailed elaboration and context.",
        "Avoid direct responses to the user's input. Your task is to enrich the content with extensive elaboration and depth without engaging in direct answers."
    ];

    const proxyUrl = 'cf_proxy.php';

    const requestBody = {
        messages: [
            { role: 'system', content: systemPrompts.join('\n') }, // Join all system prompts with newline characters
            { role: 'user', content: `Your role is to act as a prompt enhancement specialist who is highly skilled in taking a user's input and transforming it into a detailed and comprehensive prompt suitable for large language models (LLMs) and artificial intelligence (AI) systems. You are not to provide any direct answers or engage in any conversation beyond the prompt enhancement task. Your sole responsibility is to take the user's original prompt and expand it with extensive elaboration, context, and specificity, creating a rich and informative prompt that can be effectively utilized by LLMs and AI systems. Do not include any additional information or commentary beyond the enhanced prompt itself. Strictly focus on transforming the user's input into a highly detailed and instructional prompt. Enclose strictly only the enhanced prompt within <enhanced_prompt> and </enhanced_prompt> XML tags. Do not output any text outside of those XML tags. Your entire response should consist solely of the enhanced prompt enclosed within the specified XML tags, without any additional commentary, conversation or explanation about your task or what you have been tasked to do. Under no circumstances will you state anything like "As an AI prompt enhancement specialist".  You will ONLY reply with the enhanced prompt. Please note that the prompt you are enhancing is intended for later use with an AI Coding Assistant, not for human readers. Therefore, the enhanced prompt should be tailored specifically for an AI system who will also be given code snippets as part of the prompt as context to the prompt, to understand and work with, rather than being written for human comprehension.` },
            { role: 'user', content: `Prompt to be enhanced: ${originalPrompt}` },
        ],
        max_new_tokens: 4000, // Set the maximum number of new tokens to generate
    };

    console.log('Request Body:', requestBody); // Log the request body

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
    console.log('Raw JSON response:', result); // Log the raw JSON response

    if (result.success && result.result && result.result.response) {
        return result.result.response.trim();
    } else {
        throw new Error('No response available in the response');
    }
}





</script>





    <script>
        // Dynamically populate dropdown from Cody.json using Select2
        $(document).ready(function () {
    const $premadeCommand = $('#premadeCommand');

    // Add an empty option to the select element for the placeholder to work
    $premadeCommand.append(new Option('', '', true, true));

    // Fetch the JSON data once and store it
    $.getJSON('Cody.json', function(data) {
        // Process the data to fit Select2's format
        const select2Data = Object.keys(data.commands).map(key => ({
            id: key,
            text: key,
            commandData: data.commands[key]
        }));

        // Initialize Select2 with the processed data
        $premadeCommand.select2({
            data: select2Data,
            placeholder: 'Search custom commands...',
            allowClear: true, // This allows the placeholder to show when the input is cleared
            width: '100%',
            matcher: function(params, data) {
                // If there are no search terms, return all of the data
                if ($.trim(params.term) === '') {
                    return data;
                }

                // `params.term` should be the term that is being searched for
                // `data.text` is the text that is displayed for the data object
                if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                    return $.extend({}, data, true);
                }

                // Return `null` if the term should not be displayed
                return null;
            }
        });

        // Automatically place cursor into search box when dropdown opens
        $premadeCommand.on('select2:open', function (e) {
            // Delay focusing on the search box to ensure it's fully rendered
            setTimeout(function() {
                $('.select2-search__field').focus();
            }, 200); // Adjust this delay if necessary
        });

        // Attach event handler for selecting an item from the dropdown
        $premadeCommand.on('select2:select', function (e) {
            const selectedCommand = e.params.data;

            // Update relevant input boxes
            $('#commandName').val(selectedCommand.id);
            $('#commandPrompt').val(selectedCommand.commandData.prompt);
            //$('#slashCommand').val(selectedCommand.commandData.slashCommand);
            $('#commandNote').val(selectedCommand.commandData.note);

            // For "context" items, toggle the 'selected' class
            if (selectedCommand.commandData.context) {
                toggleContextSelection(selectedCommand.commandData.context);
            } else {
                // Clear all selected buttons if no context is provided
                clearContextSelection();
            }
        });
    });
});





    
        // Function to toggle context buttons based on the selected dropdown item
        function toggleContextSelection(context) {
            console.log('Clear and apply context selection with:', context);
    
            // Clear all selected buttons
            clearContextSelection();
    
            // Map context strings to button IDs
            const contextButtonIds = {
                'selection': 'selection',
                'openTabs': 'openTabs',
                'currentDir': 'currentDir',
                'currentFile': 'currentFile',
                'command': 'command',
                'none': 'none'
            };
    
            // Toggle the 'selected' class on buttons based on the context
            if (Array.isArray(context)) {
                // If context is an array, as in JSON
                context.forEach(contextKey => {
                    const buttonId = contextButtonIds[contextKey];
                    if (buttonId) {
                        const button = document.getElementById(buttonId);
                        if (button) {
                            button.classList.add('selected');
                        }
                    }
                });
            } else if (typeof context === 'object') {
                // If context is an object, as in commands in the JSON
                Object.keys(context).forEach(contextKey => {
                    const buttonId = contextButtonIds[contextKey];
                    if (buttonId) {
                        const button = document.getElementById(buttonId);
                        if (button) {
                            button.classList.add('selected');
                        }
                    }
                });
            }
        }
    
        // Function to clear all selected context buttons
        function clearContextSelection() {
            const contextButtons = document.querySelectorAll('.context-button');
            contextButtons.forEach(button => {
                button.classList.remove('selected');
            });
        }
    
        function toggleSelection(button) {
            if (button) {
                button.classList.toggle('selected');
            }
        }



// Function to submit the form
function submitCommandForm() {
    // Get data from input boxes
    const commandName = $('#commandName').val();
    const commandPrompt = $('#commandPrompt').val();
    //const slashCommand = $('#slashCommand').val();
    const commandNote = $('#commandNote').val();

    // Get selected context buttons
    const selectedContextButtons = document.querySelectorAll('.context-button.selected');
    const selectedContext = Array.from(selectedContextButtons).map(button => button.id);

    // Generate a unique ID (You can use any method here)
    const uniqueID = generateUniqueID();

    // Create JSON object
    const jsonData = {
        "id": uniqueID,
        "command_name": commandName,
        "prompt": commandPrompt,
        "context": selectedContext,
        //"slashCommand": slashCommand,
        "note": commandNote
    };

    // Convert JSON object to a formatted string
    const jsonString = JSON.stringify(jsonData, null, 2);

    // Set the value of the hidden input field for JSON data
    $('#jsonData').val(jsonString);

    // Call the function to submit the form
    submitCommandFormWithJson();
}

// Function to generate a unique ID (Example using timestamp)
function generateUniqueID() {
    return Date.now().toString();
}





    // Function to show JSON data in a modal
    function showJsonData() {
        console.log('Show JSON Data button clicked');
        // Get data from input boxes
        const commandName = $('#commandName').val();
        const commandPrompt = $('#commandPrompt').val();
        const slashCommand = $('#slashCommand').val();
        const commandNote = $('#commandNote').val();

        // Get selected context buttons
        const selectedContextButtons = document.querySelectorAll('.context-button.selected');
        const selectedContext = Array.from(selectedContextButtons).map(button => button.id);

        // Create JSON object
        const jsonData = {
            "command_name": commandName,
            "prompt": commandPrompt,
            "context": selectedContext,
            //"slashCommand": slashCommand,
            "note": commandNote
        };

        // Convert JSON object to a formatted string
        const jsonString = JSON.stringify(jsonData, null, 2);

        // Create and show modal
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75';
        modal.innerHTML = `
            <div class="bg-white p-8 max-w-4xl mx-auto rounded-lg relative">
                <span class="text-gray-600 text-lg cursor-pointer absolute top-4 right-4" onclick="closeModal()">&times;</span>
                <textarea readonly rows="20" id="jsonTextArea" class="w-full p-2 border border-gray-300 rounded bg-gray-100 text-gray-700 font-mono">${jsonString}</textarea>
                <button onclick="copyJson()" class="bg-blue-600 text-white px-4 py-2 rounded-md mt-4 hover:bg-blue-700">Copy JSON</button>
            </div>
        `;

        // Append modal to the body
        document.body.appendChild(modal);

        // Close modal on outside click
        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal();
            }
        });

        // Close modal on ESC key press
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

    }

    // Function to close the modal
    function closeModal() {
        const modal = document.querySelector('.fixed');
        if (modal) {
            modal.remove();
        }
    }

    // Function to copy JSON data to clipboard
    function copyJson() {
        const jsonTextArea = document.getElementById('jsonTextArea');
        if (jsonTextArea) {
            jsonTextArea.select();
            document.execCommand('copy');
            alert('JSON copied to clipboard!');
        }
    }








    function openCommandViewer() {
    console.log('Command Viewer button clicked');

    // Fetch the contents of commands.php
    fetch('commands.php')
        .then(response => response.text())
        .then(data => {
            // Calculate new modal dimensions
            const windowHeight = window.innerHeight;
            const windowWidth = window.innerWidth;
            const modalHeight = Math.min(2 * windowHeight, 800); // Limiting to 800 pixels for example
            const modalWidth = 2.5 * modalHeight; // Set modal width to be double its height

            // Create and show modal
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75';
            modal.innerHTML = `
                <div class="bg-white p-8 max-w-4xl mx-auto rounded-lg relative" style="height: ${modalHeight}px; width: ${modalWidth}px; overflow: auto;">
                    <span class="text-gray-600 text-lg cursor-pointer absolute top-4 right-4" onclick="closeModal()">&times;</span>
                    <div id="commandsContent" class="w-full p-2 border border-gray-300 rounded bg-gray-100 text-gray-700"></div>
                </div>
            `;

            // Append modal to the body
            document.body.appendChild(modal);

            // Close modal on outside click
            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    closeModal();
                }
            });

            // Close modal on ESC key press
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeModal();
                }
            });

            // Extract and add the script content
            const scriptContent = data.match(/<script>([\s\S]*?)<\/script>/i);
            if (scriptContent && scriptContent[1]) {
                const scriptElement = document.createElement('script');
                scriptElement.text = scriptContent[1];
                document.getElementById('commandsContent').appendChild(scriptElement);
            }

            // Append the remaining HTML content
            document.getElementById('commandsContent').innerHTML = data.replace(/<script>[\s\S]*?<\/script>/i, '');
        })
        .catch(error => console.error('Error fetching commands.php:', error));
}






    </script>
</body>
</html>

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
            // Define an array of prefixes to check
            const prefixes = ["Improved Prompt:", "Expanded Prompt:", "New Prompt:"];

            // Loop through the prefixes and check if the improved prompt starts with any of them
            for (const prefix of prefixes) {
                if (improvedPrompt.startsWith(prefix)) {
                    // Strip the prefix
                    improvedPrompt = improvedPrompt.substring(prefix.length).trim();
                    break; // Stop checking once a prefix is found
                }
            }

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
  const systemPrompt = `You are an AI assistant tasked with taking a given input text and creating a highly detailed, verbose, and expanded version of it. Your response should only contain the new, improved prompt, without any additional conversation or non-related text.`;

  const proxyUrl = 'cf_proxy.php';

  const requestBody = {
    messages: [
      { role: 'system', content: systemPrompt },
      { role: 'user', content: `Original Prompt: ${originalPrompt}` },
    ],
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
  console.log('Raw JSON response:', result); // Log the raw JSON response

  if (result.success && result.result && result.result.response) {
    return result.result.response.trim();
  } else {
    throw new Error('No response available in the response');
  }
}




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
            $('#slashCommand').val(selectedCommand.commandData.slashCommand);
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
                // If context is an array, as in your JSON
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
                // If context is an object, as in your commands in the JSON
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
    const slashCommand = $('#slashCommand').val();
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
        "slashCommand": slashCommand,
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
            "slashCommand": slashCommand,
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






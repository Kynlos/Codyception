
        // Function to be executed when the window is loaded
        window.addEventListener('load', function() {
            applyDarkModePreference(); // Apply dark mode preference
            populateDropdown(); // Populate the dropdown menu
        });

        // Google Analytics tracking code
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-FT7YJ8S06W');



        // Get the context options element
        const contextOptions = document.getElementById('contextOptions');
        let contextOptionElements = Array.from(contextOptions.getElementsByClassName('context-option'));
        let selectedContexts = [];

        // Event listener for context options
        contextOptions.addEventListener('click', function (event) {
            const option = event.target.closest('.context-option');
            if (option) {
                const value = option.getAttribute('data-value');
                option.classList.toggle('selected');
                if (option.classList.contains('selected')) {
                    selectedContexts.push(value);
                } else {
                    selectedContexts = selectedContexts.filter(context => context !== value);
                }
            }
        });

        // Event listener for create command button
        document.getElementById('createCommand').addEventListener('click', function () {
            const commandName = document.getElementById('commandName').value.trim();
            const commandPrompt = document.getElementById('commandPrompt').value.trim();
            const slashCommand = document.getElementById('slashCommand').value.trim();
            const commandNote = document.getElementById('commandNote').value.trim();

            if (!commandName || !commandPrompt) {
                alert('Please fill in all required fields.');
                return;
            }

            // Filter out 'codebase' from the selected contexts
            const filteredContexts = selectedContexts.filter(context => context !== 'codebase');

            const commandData = {
                command_name: commandName,
                prompt: commandPrompt,
                context: filteredContexts, // Use the filtered contexts here
                slashCommand: slashCommand,
                note: commandNote
            };

            saveToFile(commandData); // Save command data to file
        });

        // Function to save command data to file
        function saveToFile(commandData) {
            const jsonString = JSON.stringify(commandData, null, 2);
            const blob = new Blob([jsonString], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${commandData.command_name}.json`;
            document.body.appendChild(a);
            a.click();
            setTimeout(() => {
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            }, 100);
        }

        // Event listener for instructions button
        document.getElementById('instructionsButton').addEventListener('click', function () {
            window.open('https://sourcegraph.com/notebooks/Tm90ZWJvb2s6MzA1NQ==#the-codyjson-file-b88cc06d-c547-419f-ab15-23073a5f93ad', '_blank');
        });

        // Function to open VSCode to extension
        function openVSCodeToExtension(extensionId) {
            const vscodeUrl = `vscode:extension/${extensionId}`;
            window.open(vscodeUrl, '_blank');
        }

        // Function to populate the dropdown menu
        function populateDropdown() {
            const dropdown = document.getElementById('premadeCommands');

            fetch('Cody.json')
                .then(response => response.json())
                .then(data => {
                    Object.keys(data.commands).forEach(commandName => {
                        const option = document.createElement('option');
                        option.value = commandName;
                        option.text = commandName;
                        dropdown.add(option);
                    });
                })
                .catch(error => console.error('Error fetching Cody.json:', error));
        }

        // Event listener for window load event to populate dropdown menu
        window.addEventListener('load', populateDropdown);

        // Function to fill the form with selected command data
        function fillForm() {
            const selectedCommand = document.getElementById('premadeCommands').value;

            fetch('Cody.json')
                .then(response => response.json())
                .then(data => {
                    const selectedCommandData = data.commands[selectedCommand];

                    document.getElementById('commandName').value = selectedCommand;
                    document.getElementById('commandPrompt').value = selectedCommandData.prompt;

                    contextOptionElements.forEach(option => option.classList.remove('selected'));

                    selectedContexts = [];
                    Object.keys(selectedCommandData.context).forEach(contextValue => {
                        const option = contextOptionElements.find(option => option.getAttribute('data-value') === contextValue);
                        if (option) {
                            option.classList.add('selected');
                            selectedContexts.push(contextValue);
                        }
                    });
                })
                .catch(error => console.error('Error fetching Cody.json:', error));
        }

        // Function to toggle dark mode
        function toggleDarkMode() {
            const body = document.body;
            const container = document.querySelector('.container');
            body.classList.toggle('dark-mode');
            container.classList.toggle('dark-mode');

            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
            } else {
                localStorage.setItem('darkMode', 'disabled');
            }
        }

        // Function to apply dark mode preference
        function applyDarkModePreference() {
            const body = document.body;
            const container = document.querySelector('.container');
            const darkModePreference = localStorage.getItem('darkMode');

            if (darkModePreference === 'enabled') {
                body.classList.add('dark-mode');
                container.classList.add('dark-mode');
            }
        }

        // Event listener for window load event to apply dark mode preference and populate dropdown menu
        window.addEventListener('load', function() {
            applyDarkModePreference();
            populateDropdown();
        });

        // Function to construct command data
        function constructCommandData() {
            const commandName = document.getElementById('commandName').value.trim();
            const commandPrompt = document.getElementById('commandPrompt').value.trim();
            const slashCommand = document.getElementById('slashCommand').value.trim();
            const commandNote = document.getElementById('commandNote').value.trim();

            if (!commandName || !commandPrompt) {
                alert('Please fill in all required fields.');
                return null;
            }

            // Filter out 'codebase' from the selected contexts
            const filteredContexts = selectedContexts.filter(context => context !== 'codebase');

            const commandData = {
                command_name: commandName,
                prompt: commandPrompt,
                context: filteredContexts, // Use the filtered contexts here
                slashCommand: slashCommand,
                note: commandNote
            };

            return commandData;
        }

        // Function to show modal with JSON data
        function showModalWithJson(jsonData) {
            var modal = document.getElementById('jsonModal');
            var jsonDisplay = document.getElementById('jsonDisplay');
            var jsonString = JSON.stringify(jsonData, null, 2);

            jsonDisplay.textContent = jsonString;
            modal.style.display = "block";
        }

        // Event listener for show JSON button
        document.getElementById('showJsonButton').addEventListener('click', function() {
            const commandData = constructCommandData();
            if (commandData) {
                showModalWithJson(commandData);
            }
        });

        // Close modal when close button is clicked
        var modal = document.getElementById('jsonModal');
        var span = document.getElementsByClassName("close")[0];
        span.onclick = function() {
            modal.style.display = "none";
        }



        // Function to copy JSON to clipboard
        function copyJsonToClipboard() {
            var jsonDisplay = document.getElementById('jsonDisplay');
            var range = document.createRange();
            range.selectNode(jsonDisplay);
            window.getSelection().removeAllRanges(); 
            window.getSelection().addRange(range);
            document.execCommand('copy');
            window.getSelection().removeAllRanges(); 

            var tooltip = document.getElementById('copyTooltip');
            tooltip.classList.add('show-tooltip');
            setTimeout(function() {
                tooltip.classList.remove('show-tooltip');
            }, 1200); 
        }

        // Event listener for copy JSON button
        document.getElementById('copyJsonButton').addEventListener('click', function() {
            copyJsonToClipboard();
        });



        

        // Function to fetch and display GitHub updates
        function fetchGitHubUpdates() {
            const repoUrl = 'https://api.github.com/repos/Kynlos/Codyception/commits'; 
            const updatesBanner = document.getElementById('githubUpdatesContent');
            const repoLink = 'https://github.com/Kynlos/Codyception'; 
        
            fetch(repoUrl)
            .then(response => response.json())
            .then(commits => {
                if (commits && commits.length > 0) {
                const latestCommit = commits[0];
                const commitMessage = latestCommit.commit.message;
                const commitAuthor = latestCommit.commit.author.name;
                const commitDate = new Date(latestCommit.commit.author.date).toLocaleDateString();
                const commitUrl = latestCommit.html_url; // URL to the specific commit
        
                updatesBanner.innerHTML = `Latest update by <a href="${commitUrl}" target="_blank">${commitAuthor}</a> on ${commitDate}: <a href="${repoLink}/commit/${latestCommit.sha}" target="_blank">${commitMessage}</a>`;
                } else {
                updatesBanner.innerHTML = 'No recent updates found.';
                }
            })
            .catch(error => {
                console.error('Error fetching GitHub updates:', error);
                updatesBanner.innerHTML = 'Failed to fetch updates.';
            });
        }
        
        // Call the function to fetch and display GitHub updates
        fetchGitHubUpdates();
  

        // Function to open the Custom Command Viewer Modal
        function openCustomCommandViewerModal() {
            var modal = document.getElementById('customCommandViewerModal');
            modal.style.display = 'block';
        }
        
        // Function to close the Custom Command Viewer Modal
        function closeCustomCommandViewerModal() {
            var modal = document.getElementById('customCommandViewerModal');
            modal.style.display = 'none';
        }
        
        // Close modals when clicked outside of them
        window.onclick = function(event) {
            var jsonModal = document.getElementById('jsonModal');
            var customCommandViewerModal = document.getElementById('customCommandViewerModal');

            if (event.target == jsonModal) {
                jsonModal.style.display = "none";
            } else if (event.target == customCommandViewerModal) {
                customCommandViewerModal.style.display = 'none';
            }
        }

        // Function to close a given modal
        function closeModal(modalId) {
            var modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = "none";
            }
        }

        // Attach close function to the close button of jsonModal
        var jsonModalCloseBtn = document.querySelector("#jsonModal .close");
        jsonModalCloseBtn.onclick = function() {
            closeModal('jsonModal');
        }

        // Attach close function to the close button of customCommandViewerModal
        var customCommandViewerModalCloseBtn = document.querySelector("#customCommandViewerModal .close");
        customCommandViewerModalCloseBtn.onclick = function() {
            closeModal('customCommandViewerModal');
        }

        // Function to toggle the sidebar
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active'); // Use the appropriate class that shows/hides the sidebar
        }

        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            //console.log('Before toggle, active class:', sidebar.classList.contains('active'));
            sidebar.classList.toggle('active'); // Use the appropriate class that shows/hides the sidebar
            //console.log('After toggle, active class:', sidebar.classList.contains('active'));
        }

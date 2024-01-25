
        window.addEventListener('load', function() {
            applyDarkModePreference();
            populateDropdown();
        });
        
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-FT7YJ8S06W');
    
        function toggleSidebar() {
            document.body.classList.toggle('body-sidebar-open');
        }

        const contextOptions = document.getElementById('contextOptions');
        let contextOptionElements = Array.from(contextOptions.getElementsByClassName('context-option'));
        let selectedContexts = [];
    
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
    
        document.getElementById('createCommand').addEventListener('click', function () {
            const commandName = document.getElementById('commandName').value.trim();
            const commandPrompt = document.getElementById('commandPrompt').value.trim();
            const slashCommand = document.getElementById('slashCommand').value.trim();
            const commandNote = document.getElementById('commandNote').value.trim();
    
            if (!commandName || !commandPrompt) {
                alert('Please fill in all required fields.');
                return;
            }
    
            const commandData = {
                command_name: commandName,
                prompt: commandPrompt,
                context: selectedContexts,
                slashCommand: slashCommand,
                note: commandNote
            };
    
            saveToFile(commandData);
        });
    
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
    
        document.getElementById('instructionsButton').addEventListener('click', function () {
            window.open('https://sourcegraph.com/notebooks/Tm90ZWJvb2s6MzA1NQ==#the-codyjson-file-b88cc06d-c547-419f-ab15-23073a5f93ad', '_blank');
        });
    
        function openVSCodeToExtension(extensionId) {
            const vscodeUrl = `vscode:extension/${extensionId}`;
            window.open(vscodeUrl, '_blank');
        }
    
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
    
        window.addEventListener('load', populateDropdown);
    
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

    function applyDarkModePreference() {
        const body = document.body;
        const container = document.querySelector('.container');
        const darkModePreference = localStorage.getItem('darkMode');

        if (darkModePreference === 'enabled') {
            body.classList.add('dark-mode');
            container.classList.add('dark-mode');
        }
    }

    window.addEventListener('load', function() {
        applyDarkModePreference();
        populateDropdown();
    });



    function constructCommandData() {
        const commandName = document.getElementById('commandName').value.trim();
        const commandPrompt = document.getElementById('commandPrompt').value.trim();
        const slashCommand = document.getElementById('slashCommand').value.trim();
        const commandNote = document.getElementById('commandNote').value.trim();

        if (!commandName || !commandPrompt) {
            alert('Please fill in all required fields.');
            return null;
        }

        const commandData = {
            command_name: commandName,
            prompt: commandPrompt,
            context: selectedContexts,
            slashCommand: slashCommand,
            note: commandNote
        };

        return commandData;
    }

    function showModalWithJson(jsonData) {
        var modal = document.getElementById('jsonModal');
        var jsonDisplay = document.getElementById('jsonDisplay');
        var jsonString = JSON.stringify(jsonData, null, 2);

        jsonDisplay.textContent = jsonString;
        modal.style.display = "block";
    }

    document.getElementById('showJsonButton').addEventListener('click', function() {
        const commandData = constructCommandData();
        if (commandData) {
            showModalWithJson(commandData);
        }
        });
    var modal = document.getElementById('jsonModal');

    var span = document.getElementsByClassName("close")[0];

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }


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

    document.getElementById('copyJsonButton').addEventListener('click', function() {
        copyJsonToClipboard();
    });

<?php
// Start session
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: command_builder.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Codyception</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Include Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="cmdbuilderFuncs.js"></script>


<style>
  .darkmode-toggle {
    position: fixed;
    top: 1rem;
    right: 1rem;
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



</style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
<header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="#" class="text-xl font-bold text-gray-800">Codyception</a>
                </div>
                <div class="flex items-center">
                <!--<a href="https://donate.stripe.com/aEU28ja9qeCRc6ceUU" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition-colors duration-300 mr-4">
                    <i class="fas fa-heart mr-2"></i> Support The Project
                </a>-->
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a href="public.php" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-list"></i> Browse</a>
                        <a href="#" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-question-circle"></i> Help</a>
                        <a href="https://marketplace.visualstudio.com/items?itemName=Kynlo.codyception" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-puzzle-piece"></i> Extension</a>
                        <a href="register.php" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fab fa-github"></i> Register/Login</a>

                        <?php else: ?>

                        <a href="dashboard.php" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                        <a href="command_builder.php" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-plus-square"></i> Create</a>
                        <a href="public.php" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-folder-open"></i> Browse</a>
                        <a href="https://marketplace.visualstudio.com/items?itemName=Kynlo.codyception" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-puzzle-piece"></i> Plugin</a>
                        <a href="#" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-question-circle"></i> Help</a>
                        <a href="logout.php" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fas fa-sign-out-alt"></i> Logout</a>

                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>


<main class="container mx-auto px-6 py-8 pt-10">
    <div class="flex flex-col items-center justify-center pt-10">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Codyception: The Ultimate Command Creation Tool for Cody AI</h1>
        <p class="text-gray-600 mb-8 text-center">Create custom commands for Cody AI, <a href="public.php" class="text-blue-500 hover:text-blue-800 visited:text-blue-500">share them with the world</a>, and discover new commands created by other users.<br /><a href="register.php" class="text-blue-500 hover:text-blue-800 visited:text-blue-500">Register an account or login</a> to save your commands, share commands, and more!</p>



    </div>


    <!-- 3. Main Content Area -->
    <main class="my-0.25 flex-grow">

    <div class="max-w-5xl mx-auto px-2">

        <!-- 4. Select Pre-made Command Dropdown -->
        <section class="my-2">
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

<!-- Command Prompt Section -->
<section class="my-8">
    <div class="max-w-3xl mx-auto">
        <label for="commandPrompt" class="block text-lg font-semibold text-gray-700 mb-2">Command Prompt (Prompt to send to Cody.):</label>
        <div class="flex">
            <textarea id="commandPrompt" name="commandPrompt" class="mt-1 p-3 border border-gray-300 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-600" rows="4"></textarea>
            <button id="improvePromptButton" class="ml-2 bg-gray-300 text-gray-600 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-600 cursor-not-allowed" disabled title="Please login to use this feature">
                <i class="fas fa-robot mb-1 block"></i> 
                <span class="inline-block">Automatically Improve Prompt With AI<br />(Login Required)</span>
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


        <!-- 9. Command Note -->
        <section class="my-8">
            <div class="max-w-3xl mx-auto">
                <label for="commandNote" class="block text-lg font-semibold text-gray-700 mb-2">Command Note (Optional):</label>
                <div class="flex">
                    <textarea id="commandNote" name="commandNote" class="mt-1 p-3 border border-gray-300 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-600" rows="2"></textarea>
                    <button id="summarizePromptButton" class="ml-2 bg-gray-300 text-gray-600 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-600 cursor-not-allowed" disabled title="Please login to use this feature">

                    <i class="fas fa-file-alt mr-2"></i>
                        <span class="inline-block">Summarize Prompt<br />(Login Required)</span>
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
                    <!--<button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-600 mb-4 flex justify-center items-center">Save Command To Account</button>-->
                    <button type="button" onclick="showJsonData()" class="w-full bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-600 mb-4 flex justify-center items-center">Show JSON Data</button>
                </form>

            </div>
        </section>


    </div>
</main>



    </div>
</main>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">



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


</body>
</html>

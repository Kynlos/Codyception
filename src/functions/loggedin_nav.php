<?php

// Logged in navigation function
function loggedin_nav() {
  echo '<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="index.php" class="text-xl font-bold text-gray-800">Codyception</a>
                </div>
                <div class="flex items-center">
                    <a href="command_builder.php" class="mx-2 text-gray-600 hover:text-gray-800">Commands</a>
                    <a href="profile.php" class="mx-2 text-gray-600 hover:text-gray-800">Profile</a>
                    <a href="logout.php" class="mx-2 text-gray-600 hover:text-gray-800">Logout</a>
                </div>
            </div>
        </nav>
    </header>';
}

?>

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
    <!--<script src="https://cdn.jsdelivr.net/npm/darkmode-js@1.5.7/lib/darkmode-js.min.js"></script>
<script>
  function addDarkmodeWidget() {
    new Darkmode().showWidget();
  }
  window.addEventListener('load', addDarkmodeWidget);
  function removeDarkmodeToggle() {
    const toggle = document.querySelector('.darkmode-toggle');
    toggle.parentNode.removeChild(toggle);
  }
  window.addEventListener('load', removeDarkmodeToggle);
</script>-->

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
                        <a href="register.php" class="mx-2 text-gray-600 hover:text-gray-800"><i class="fab fa-github"></i> Login</a>

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






<!--<main class="container mx-auto px-6 py-8 pt-10">
<div class="flex flex-col items-center justify-center pt-10">
    <h1 class="text-4xl font-bold text-gray-800 mb-4">Codyception for Enterprise</h1>
    <p class="text-gray-600 mb-8 max-w-3xl text-center leading-relaxed">
        Codyception for Enterprise is the ultimate command creation solution tailored specifically for enterprise needs. It offers unparalleled control, security, and flexibility for managing custom commands within your organization. 
        With Codyception for Enterprise, you gain access to a suite of advanced features designed to empower your teams and drive innovation.

    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
        <div class="bg-gray-200 shadow-md rounded-lg p-6 hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-bold text-gray-800 mb-2">Complete Data Control</h2>
            <p class="text-gray-600">With Codyception for Enterprise, you have full control over your command data. Host all your custom commands in-house, ensuring sensitive information stays within your organization's secure environment.</p>
        </div>

        <div class="bg-gray-200 shadow-md rounded-lg p-6 hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-bold text-gray-800 mb-2">Enhanced Security Measures</h2>
            <p class="text-gray-600">Security is paramount for enterprise operations. Codyception for Enterprise implements robust security measures, including encryption, access controls, and audit logs, to safeguard your command creation process.</p>
        </div>

        <div class="bg-gray-300 shadow-md rounded-lg p-6 hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-bold text-gray-800 mb-2">Scalability and Customization</h2>
            <p class="text-gray-600">Whether you have a small team or a large enterprise, Codyception for Enterprise scales with your needs. Customize workflows, integrate with existing tools, and adapt the platform to fit your organization's unique requirements.</p>
        </div>
    </div>

    <p class="text-gray-600 mt-8">Experience seamless command creation and management tailored for enterprise environments. Empower your teams with Codyception for Enterprise and unlock the full potential of custom commands within your organization.
    <br><br>
        <span class="font-semibold">Complete Data Control:</span> With Codyception for Enterprise, you have full control over your command data. Host all your custom commands in-house, ensuring sensitive information stays within your organization's secure environment.
        <br><br>
        <span class="font-semibold">Enhanced Security Measures:</span> Security is paramount for enterprise operations. Codyception for Enterprise implements robust security measures, including encryption, access controls, and audit logs, to safeguard your command creation process.
        <br><br>
        <span class="font-semibold">Scalability and Customization:</span> Whether you have a small team or a large enterprise, Codyception for Enterprise scales with your needs. Customize workflows, integrate with existing tools, and adapt the platform to fit your organization's unique requirements.
        <br><br>
        Experience seamless command creation and management tailored for enterprise environments. Empower your teams with Codyception for Enterprise and unlock the full potential of custom commands within your organization.


    </p>
</div>

        
        <div class="mt-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Contact Us for Pricing/Info</h2>
            <p class="text-gray-600 mb-6">Interested in learning more about Codyception for Enterprise or obtaining pricing information? Contact our team today!</p>

            
            <div class="max-w-lg mx-auto bg-white rounded-lg shadow-md p-8">
                <form action="#" method="POST">
                    <div class="mb-4">
                        <label for="companySize" class="block text-gray-800 font-semibold mb-2">Company Size<span class="text-red-600">*</span></label>
                        <select id="companySize" name="companySize" class="form-select w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="1-50">1-50 Employees</option>
                            <option value="51-100">51-100 Employees</option>
                            <option value="101-500">101-500 Employees</option>
                            <option value="501+">501+ Employees</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="companyName" class="block text-gray-800 font-semibold mb-2">Company Name<span class="text-gray-400">(Optional)</span></label>
                        <input type="text" id="companyName" name="companyName" class="form-input w-full bg-gray-100 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="yourName" class="block text-gray-800 font-semibold mb-2">Your Name<span class="text-red-600">*</span></label>
                        <input type="text" id="yourName" name="yourName" class="form-input w-full bg-gray-100 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="yourEmail" class="block text-gray-800 font-semibold mb-2">Your Email<span class="text-red-600">*</span></label>
                        <input type="email" id="yourEmail" name="yourEmail" class="form-input w-full bg-gray-100 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="otherInfo" class="block text-gray-800 font-semibold mb-2">Any other info<span class="text-red-600">*</span></label>
                        <textarea id="otherInfo" name="otherInfo" class="form-textarea w-full bg-gray-100 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required></textarea>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500">Submit</button>
                </form>
        </div>
    </div>
</main>-->





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
    .enterprise-link {
        float: right;
    }
    .footer-icon {
        margin-right: 5px;
    }
</style>

<footer class="bg-gray-800 text-white py-4">
    <div class="container mx-auto px-6">
        <div class="float-left copyright">
            <i class="fas fa-copyright footer-icon"></i> <?php echo date('Y'); ?> Codyception. All rights reserved.
        </div>
        <div class="float-right enterprise-link">
            <i class="fas fa-building footer-icon"></i><a href="#">Codyception for Enterprise</a>
        </div>
    </div>
</footer>


</body>
</html>

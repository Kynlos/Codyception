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
                    <a href="index.php" class="text-xl font-bold text-gray-800">Codyception - Privacy Policy</a>
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
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Privacy Policy</h1>
        <p class="text-gray-600 mb-8 text-center">Codyception</p>

    <!-- Audio Player -->
    <div class="container mx-auto px-6 py-8">
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Listen to our Privacy Policy</h2>
        <audio controls class="w-full bg-gray-200 rounded-lg p-4">
            <source src="privacy.wav" type="audio/wav">
            Your browser does not support the audio element.
        </audio>
    </div>

    </div>


    <main class="container mx-auto px-6 py-8 pt-10">
    <div>
        <!-- Privacy Policy -->
        <h2 class="text-2xl font-semibold mb-4">Privacy Policy</h2>
        <p><strong>Effective Date:</strong> March 17, 2024</p>
        <br>
        <p><strong>Introduction</strong></p>
        <p>This Privacy Policy outlines the policies and procedures of Codyception ("we", "us", or "our") regarding the collection, use, and disclosure of personal information when you use our website, products, and services (collectively, the "Services"). We are committed to compliance with applicable data protection laws, including the General Data Protection Regulation (GDPR) and Cookie laws.</p>
        <br>
        <p><strong>Information We Collect</strong></p>
        <p>We collect the following types of personal information from you:</p>
        <ul>
            <li>• Contact information: such as your name, email address, and phone number.</li>
            <li>• Usage data: such as your IP address, browser type, operating system, and the pages you visit on our website.</li>
            <li>• Content you provide: such as comments, posts, and messages you submit through our Services.</li>
            <li>• Whatever data GitHub sends us for you to register (we only store your GitHub ID. We also store your email address if GitHub provides it but it isn't mandatory).</li>
            <li>• Other information: such as your age, gender, and interests, if you choose to provide it.</li>
        </ul>
        <br>
        <p><strong>How We Use Your Information</strong></p>
        <p>We use your personal information to:</p>
        <ul>
            <li>• Provide and improve our Services.</li>
            <li>• Send you marketing and promotional materials. (This is very unlikely beyond maybe one (1) email per year.)</li>
            <li>• Respond to your inquiries and requests.</li>
            <li>• Conduct research and analysis.</li>
            <li>• Prevent fraud and abuse.</li>
        </ul>
        <br>
        <p><strong>How We Share Your Information</strong></p>
        <p>We may share your personal information with the following third parties:</p>
        <ul>
            <li>• Service providers: such as our hosting provider, email marketing provider, and payment processor(s).</li>
            <li>• Business partners: such as our marketing partners and affiliates.</li>
            <li>• Law enforcement and government agencies: if required by law or to protect our rights.</li>
        </ul>
        <br>
        <p><strong>Cookies and Tracking Technologies</strong></p>
        <p>We use cookies and similar tracking technologies on our website. We use these to simply track our traffic and to see where things fail. Third-party providers may also set and access cookies on your device to track information about your browsing activities.</p>
        <br>
        <p><strong>Handling of Data Sent to or Received by AI/LLM Services</strong></p>
        <p>We use AI services from Cloudflare for security purposes and to provide AI features on our website. We do not retain any data sent to or received by any AI/LLM (Artificial Intelligence/Large Language Model) services on our website for any purpose other than to be stored in the user's account at their own request. This data can be deleted by the user at any time in their account settings. Cloudflare's privacy policy can be found <a href="https://www.cloudflare.com/en-gb/privacypolicy/">here</a>.</p>
        <br>
        <p><strong>Your Rights</strong></p>
        <p>You have the following rights with respect to your personal information:</p>
        <ul>
            <li>• The right to access your personal information.</li>
            <li>• The right to rectify your personal information.</li>
            <li>• The right to erase your personal information.</li>
            <li>• The right to restrict the processing of your personal information.</li>
            <li>• The right to object to the processing of your personal information.</li>
            <li>• The right to data portability.</li>
        </ul>
        <br>
        <p><strong>Data Security</strong></p>
        <p>We take reasonable measures to protect your personal information from unauthorized access, use, disclosure, alteration, and destruction. However, no security measures are perfect, and we cannot guarantee the security of your personal information.</p>
        <br>
        <p><strong>Changes to this Privacy Policy</strong></p>
        <p>We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on our website.</p>
        <br>
        <p><strong>Contact Us</strong></p>
        <p>If you have any questions about this Privacy Policy, please contact us at <a href="mailto:email@example.com">kynlo@codyception.com</a>.</p>
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

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
?>
<header class="bg-gray-800 text-white shadow">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
        <!-- Logo and Title -->
        <div class="flex items-center space-x-4">
            <img src="logo.png" alt="Blog Logo" class="h-8 w-8">
            <a href="index.php" class="text-2xl font-bold">EscadeDev</a>
        </div>
        <!-- Navigation Menu -->
        <nav class="hidden md:flex space-x-4">
            <a href="index.php" class="text-gray-300 hover:text-gray-100 hover:bg-gray-600 p-1 rounded-md">Home</a>
            <a href="about.php" class="text-gray-300 hover:text-gray-100 hover:bg-gray-600 p-1 rounded-md">About</a>
            <a href="blog.php" class="text-gray-300 hover:text-gray-100 hover:bg-gray-600 p-1 rounded-md">Blog</a>
            <a href="contact.php" class="text-gray-300 hover:text-gray-100 hover:bg-gray-600 p-1 rounded-md">Contact</a>
            <a href="admin-dashboard.php" class="text-gray-300 hover:text-gray-100 hover:bg-gray-600 p-1 rounded-md">Admin</a>

        </nav>

        <div class="text-2xl text-gray-200 font-bold bg-blue-600 p-2 rounded-md hidden md:visible">
            <a href="create_post_form.php">+</a>
        </div>

        <!-- Search Bar and User Info -->
        <div class="flex items-center space-x-2">
            <form action="search.php" method="GET" class="hidden" md:block">
                <input type="text" name="query" class="px-3 py-2 border border-gray-600 rounded-md focus:outline-none focus:ring focus:ring-indigo-400 focus:border-indigo-500 bg-gray-700 text-white" placeholder="Search...">
            </form>            
            <?php if ($user): ?>
                <div class="flex items-center space-x-2">
                    <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Image" class="h-8 w-8 rounded-full">
                    <span class="text-gray-300"><?php echo htmlspecialchars($user['username']); ?></span>
                    <a href="logout.php" class="hidden md:block text-gray-300 hover:text-gray-100 hover:bg-gray-600 rounded-md p-1">Logout</a>
                </div>
            <?php else: ?>
                
                <div class="hidden md:block">
                    <a href="login.php" class="text-gray-300 hover:text-gray-100 hover:bg-gray-600 rounded-md p-1">Login</a>
                    <a href="signup.php" class="text-gray-300 hover:text-gray-100 hover:bg-gray-600 rounded-md p-1">Register</a>
                </div>
            <?php endif; ?>
        </div>
        <!-- Mobile Menu Button -->
        <button id="mobile-menu-button" class="md:hidden text-gray-300 focus:outline-none focus:text-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden">
        <nav class="px-2 pt-2 pb-4 space-y-1 sm:px-3">
        <form action="search.php" method="GET" class=" md:block">
                <input type="text" name="query" class="px-3 py-2 border border-gray-600 rounded-md focus:outline-none focus:ring focus:ring-indigo-400 focus:border-indigo-500 bg-gray-700 text-white" placeholder="Search...">
            </form>            
            <a href="index.php" class="block text-gray-300 hover:text-gray-100">Home</a>
            <a href="about.php" class="block text-gray-300 hover:text-gray-100">About</a>
            <a href="blog.php" class="block text-gray-300 hover:text-gray-100">Blog</a>
            <a href="contact.php" class="block text-gray-300 hover:text-gray-100">Contact</a>
            <?php if ($user): ?>
                <a href="logout.php" class="block text-gray-300 hover:text-gray-100">Logout</a>
            <?php else: ?>
                <a href="login.php" class="block text-gray-300 hover:text-gray-100">Login</a>
                <a href="signup.php" class="block text-gray-300 hover:text-gray-100">Register</a>
            <?php endif; ?>
            <a href="admin-dashboard.php" class="text-gray-300 hover:text-gray-100 hover:bg-gray-600 p-1 rounded-md">Admin</a>
        </nav>
    </div>
</header>
<hr>

<script>
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        var mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.remove('hidden');
        } else {
            mobileMenu.classList.add('hidden');
        }
    });
</script>

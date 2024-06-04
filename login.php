<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 flex items-center justify-center h-screen">
    <div class="w-full max-w-md bg-gray-800 p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-white">Login</h2>
        <?php
        session_start();
        $errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
        session_unset();
        ?>
        <form action="login_process.php" method="post" class="space-y-4">
            <div>
                <label for="email" class="block text-gray-300">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 text-white">
                <div class="text-red-500 text-sm"><?php echo $errors['email'] ?? ''; ?></div>
            </div>

            <div>
                <label for="password" class="block text-gray-300">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 text-white">
                <div class="text-red-500 text-sm"><?php echo $errors['password'] ?? ''; ?></div>
            </div>

            <div>
                <input type="submit" value="Login" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="text-center">
                <p class="text-gray-400">Don't have an account? <a href="signup.php" class="text-blue-500 hover:underline">Create one</a></p>
            </div>
        </form>
    </div>
</body>
</html>

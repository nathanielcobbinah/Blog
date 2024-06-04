<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 flex items-center justify-center h-screen">
    <div class="w-full max-w-md bg-gray-800 p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-white">Signup Form</h2>
        <?php
        $errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
        $old = isset($_SESSION['old']) ? $_SESSION['old'] : [];
        session_unset();
        ?>
        <form action="signup_process.php" method="post" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label for="username" class="block text-gray-300">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($old['username'] ?? '', ENT_QUOTES); ?>" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 text-white" placeholder="Choose a username">
                <div class="text-red-500 text-sm"><?php echo $errors['username'] ?? ''; ?></div>
            </div>

            <div>
                <label for="email" class="block text-gray-300">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($old['email'] ?? '', ENT_QUOTES); ?>" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 text-white" placeholder="Enter your email">
                <div class="text-red-500 text-sm"><?php echo $errors['email'] ?? ''; ?></div>
            </div>

            <div>
                <label for="password" class="block text-gray-300">Password:</label>
                <input type="password" id="password" name="password" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 text-white" placeholder="Create a password">
                <div class="text-red-500 text-sm"><?php echo $errors['password'] ?? ''; ?></div>
            </div>

            <div>
                <label for="confirm_password" class="block text-gray-300">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 text-white" placeholder="Confirm your password">
                <div class="text-red-500 text-sm"><?php echo $errors['confirm_password'] ?? ''; ?></div>
            </div>

            <div>
                <label for="profile_image" class="block text-gray-300">Profile Image:</label>
                <input type="file" id="profile_image" name="profile_image" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 text-white">
                <div class="text-red-500 text-sm"><?php echo $errors['profile_image'] ?? ''; ?></div>
            </div>

            <div>
                <input type="submit" value="Signup" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </form>
    </div>
</body>
</html>

<?php
include 'config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("location: login.php");
    exit();
}

$username = $email = $password = $role = "";
$username_err = $email_err = $password_err = $role_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $username_err = "Please enter a username.";
    } else {
        $username = test_input($_POST["username"]);
    }

    if (empty($_POST["email"])) {
        $email_err = "Please enter an email.";
    } else {
        $email = test_input($_POST["email"]);
    }

    if (empty($_POST["password"])) {
        $password_err = "Please enter a password.";
    } else {
        $password = password_hash(test_input($_POST["password"]), PASSWORD_DEFAULT);
    }

    if (empty($_POST["role"])) {
        $role_err = "Please select a role.";
    } else {
        $role = test_input($_POST["role"]);
    }

    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($role_err)) {
        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssss", $username, $email, $password, $role);
            if ($stmt->execute()) {
                header("location: admin_manage_users.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }
    }

    $conn->close();
}
?>

<?php $page_title = "Add User"; ?>
<?php include 'head.php'; ?>

<body class="bg-gray-900 text-white">

    <!-- Header -->
    <?php include 'admin_header.php'; ?>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-2xl mb-4">Add User</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-4">
                    <label for="username" class="block mb-2">Username:</label>
                    <input type="text" id="username" name="username" class="w-full px-3 py-2 text-gray-800 border rounded" required>
                    <span class="text-red-500"><?php echo $username_err; ?></span>
                </div>
                <div class="mb-4">
                    <label for="email" class="block mb-2">Email:</label>
                    <input type="email" id="email" name="email" class="w-full px-3 py-2 text-gray-800 border rounded" required>
                    <span class="text-red-500"><?php echo $email_err; ?></span>
                </div>
                <div class="mb-4">
                    <label for="password" class="block mb-2">Password:</label>
                    <input type="password" id="password" name="password" class="w-full px-3 py-2 text-gray-800 border rounded" required>
                    <span class="text-red-500"><?php echo $password_err; ?></span>
                </div>
                <div class="mb-4">
                    <label for="role" class="block mb-2">Role:</label>
                    <select id="role" name="role" class="w-full px-3 py-2 text-gray-800 border rounded" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                    <span class="text-red-500"><?php echo $role_err; ?></span>
                </div>
                <div>
                    <input type="submit" value="Submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'admin_footer.php'; ?>

</body>

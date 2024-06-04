<?php
include 'config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $username = $user['username'];
            $email = $user['email'];
            $role = $user['role'];
        } else {
            header("location: admin_manage_users.php");
            exit();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $user_id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssi", $username, $email, $role, $user_id);
        if ($stmt->execute()) {
            header("location: admin_manage_users.php");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }
    }
}

$conn->close();
?>

<?php $page_title = "Edit User"; ?>
<?php include 'head.php'; ?>

<body class="bg-gray-900 text-white">

    <!-- Header -->
    <?php include 'admin_header.php'; ?>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-2xl mb-4">Edit User</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($user_id); ?>">
                <div class="mb-4">
                    <label for="username" class="block mb-2">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" class="w-full px-3 py-2 text-gray-800 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block mb-2">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="w-full px-3 py-2 text-gray-800 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="role" class="block mb-2">Role:</label>
                    <select id="role" name="role" class="w-full px-3 py-2 text-gray-800 border rounded" required>
                        <option value="user" <?php echo ($role == 'user') ? 'selected' : ''; ?>>User</option>
                        <option value="admin" <?php echo ($role == 'admin') ? 'selected' : ''; ?>>Admin</option>
                    </select>
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

<?php
include 'config.php';

// Check if there are any admin users in the database
$sql = "SELECT COUNT(*) as admin_count FROM users WHERE role = 'admin'";
// $sql = "SELECT COUNT(*)";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$admin_count = $row['admin_count'];

// If no admin user exists, allow the current user to be promoted to admin
if ($admin_count == 0) {
    $_SESSION['user']['role'] = 'admin';
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
   //  header("location: login.php");
   //  exit();
}

// Fetch all users
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

$conn->close();
?>

<?php $page_title = "Manage Users"; ?>
<?php include 'head.php'; ?>

<body class="bg-gray-900 text-white">

    <!-- Header -->
    <?php include 'admin_header.php'; ?>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-2xl mb-4">Manage Users</h2>
            <table class="min-w-full bg-gray-700 text-gray-300">
                <thead>
                    <tr>
                        <th class="w-1/4 py-2">Username</th>
                        <th class="w-1/4 py-2">Email</th>
                        <th class="w-1/4 py-2">Role</th>
                        <th class="w-1/4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="border-t border-gray-600 py-2 px-4"><?php echo htmlspecialchars($row['username']); ?></td>
                                <td class="border-t border-gray-600 py-2 px-4"><?php echo htmlspecialchars($row['email']); ?></td>
                                <td class="border-t border-gray-600 py-2 px-4"><?php echo htmlspecialchars($row['role']); ?></td>
                                <td class="border-t border-gray-600 py-2 px-4">
                                    <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                                    <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="text-red-500 hover:text-red-700 ml-4">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-4">No users found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="mt-4">
                <a href="add_user.php" class="bg-blue-500 text-white px-4 py-2 rounded">Add New User</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'admin_footer.php'; ?>

</body>

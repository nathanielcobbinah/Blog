<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white">

    <!-- Header -->
    <?php include 'admin_header.php'; ?>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 flex flex-wrap">
        <!-- Sidebar Navigation -->
        <?php include 'admin_sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="w-full md:w-3/4 bg-gray-800 rounded-lg shadow-md p-6 overflow-x-auto">
            <!-- Content Views -->
            <h2 class="text-2xl font-semibold mb-4">Manage Categories</h2>

            <!-- Add New Category Form -->
            <form action="admin_manage_categories.php" method="post" class="mb-4">
                <div class="flex items-center">
                    <input type="text" name="category_name" placeholder="New Category Name" class="w-full px-4 py-2 text-gray-900 rounded-l-lg" required>
                    <button type="submit" name="add_category" class="bg-blue-500 text-white px-4 py-2 rounded-r-lg hover:bg-blue-600">Add</button>
                </div>
            </form>

            <!-- Categories Table -->
            <table class="w-full border-collapse border border-gray-600">
                <thead>
                    <tr>
                        <th class="py-2 px-4 bg-gray-700 border border-gray-600">Category Name</th>
                        <th class="py-2 px-4 bg-gray-700 border border-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- PHP code to fetch and display categories dynamically -->
                    <?php
                    // Include your database connection
                    include 'config.php';

                    // Add new category
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
                        $category_name = $_POST['category_name'];
                        $sql_add = "INSERT INTO categories (name) VALUES (?)";
                        if ($stmt = $conn->prepare($sql_add)) {
                            $stmt->bind_param("s", $category_name);
                            $stmt->execute();
                            $stmt->close();
                        }
                    }

                    // Delete category
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_category'])) {
                        $category_id = $_POST['category_id'];
                        $sql_delete = "DELETE FROM categories WHERE id = ?";
                        if ($stmt = $conn->prepare($sql_delete)) {
                            $stmt->bind_param("i", $category_id);
                            $stmt->execute();
                            $stmt->close();
                        }
                    }

                    // Fetch categories from the database
                    $sql_categories = "SELECT * FROM categories";
                    $result_categories = $conn->query($sql_categories);

                    if ($result_categories->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result_categories->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='py-2 px-4 border border-gray-600'>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td class='py-2 px-4 border border-gray-600'>";
                            echo "<form action='admin_manage_categories.php' method='post' class='inline-block'>";
                            echo "<input type='hidden' name='category_id' value='" . intval($row['id']) . "'>";
                            echo "<button type='submit' name='delete_category' class='bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600'>Delete</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2' class='py-2 px-4 border border-gray-600 text-center'>No categories found</td></tr>";
                    }

                    // Close database connection
                    $conn->close();
                    ?>
                    <!-- End of PHP code -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'admin_footer.php'; ?>

</body>
</html>

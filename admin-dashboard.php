<?php
// if (!isset($_SESSION['user'])) {
//     header("location: login.php");
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            <h2 class="text-2xl font-semibold mb-4">Manage Posts</h2>
            <!-- Posts Table -->
            <table class="w-full border-collapse border border-gray-600">
                <thead>
                    <tr>
                        <th class="py-2 px-4 bg-gray-700 border border-gray-600">Title</th>
                        <th class="py-2 px-4 bg-gray-700 border border-gray-600">Author</th>
                        <th class="py-2 px-4 bg-gray-700 border border-gray-600">Date</th>
                        <th class="py-2 px-4 bg-gray-700 border border-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- PHP code to fetch and display posts dynamically -->
                    <?php
                    // Include your database connection
                    include 'config.php';

                    // Fetch posts from the database
                    $sql_posts = "SELECT * FROM posts";
                    $result_posts = $conn->query($sql_posts);

                    if ($result_posts->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result_posts->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='py-2 px-4 border border-gray-600'>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td class='py-2 px-4 border border-gray-600'>" . (isset($row['author']) ? htmlspecialchars($row['author']) : 'Unknown') . "</td>";
                            echo "<td class='py-2 px-4 border border-gray-600'>" . (isset($row['created_at']) ? htmlspecialchars($row['created_at']) : 'Unknown') . "</td>";
                            echo "<td class='py-2 px-4 border border-gray-600'>";
                            // echo "<button class='bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600'>Edit</button>";
                            echo '<a href="" class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600">Edit</a>';
                            echo "<button class='bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 ml-2'>Delete</button>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='py-2 px-4 border border-gray-600 text-center'>No posts found</td></tr>";
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



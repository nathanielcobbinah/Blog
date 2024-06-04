<?php
include 'config.php';


// Fetch categories for the sidebar
$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);

// Fetch posts based on selected category
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$sql_posts = "SELECT * FROM posts";
if ($category_id) {
    $sql_posts .= " WHERE category_id = $category_id";
}
$result_posts = $conn->query($sql_posts);

// Get the current user ID
$current_user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;

?>

<?php $page_title = "Blog Posts | EscadeDev"; ?>
<?php include 'head.php'; ?>

<!-- Main Content -->
<body class="bg-gray-900 text-white">

<div class="container mx-auto px-4 py-8 flex flex-col md:flex-row space-y-8 md:space-y-0 md:space-x-8">
    <!-- Blog Posts -->
    <div class="flex-1">
        <!-- Main content for posts -->
        <div class="md:col-span-3">
            <h2 class="text-2xl mb-4 text-white">Posts</h2>
            <?php
            if ($result_posts->num_rows > 0) {
                while ($row = $result_posts->fetch_assoc()) {
                    echo "<div class='mb-8 bg-gray-800 text-white rounded-lg shadow-md overflow-hidden'>";
                    if (!empty($row['thumbnail_image'])) {
                        echo "<img src='posts_uploads/" . htmlspecialchars($row['thumbnail_image']) . "' alt='Thumbnail' class='w-full h-72 object-cover'>";
                    }
                    echo "<div class='p-4'>";
                    echo "<h3 class='text-xl mb-2'>" . htmlspecialchars($row['title']) . "</h3>";
                    echo "<p class='mb-2 text-gray-300'>" . htmlspecialchars($row['excerpt']) . "</p>";
                    echo "<a href='post.php?id=" . intval($row['id']) . "' class='text-blue-400'>Read More</a>";

                    // Show edit and delete buttons if the post belongs to the current user
                    if ($current_user_id === $row['author_id']) {
                        echo "<div class='mt-4'>";
                        echo "<a href='edit_post.php?id=" . intval($row['id']) . "' class='text-yellow-400 mr-2'>Edit</a>";
                        echo "<a href='delete_post.php?id=" . intval($row['id']) . "' class='text-red-400'>Delete</a>";
                        echo "</div>";
                    }

                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p class='text-white'>No posts found under this category.</p>";
            }
            ?>
        </div>
    </div>
    <!-- Sidebar -->
    <?php 
        include 'sidebar.php';
    ?>
</div>
</body>
<?php include 'footer.php';?>

<?php $conn->close(); ?>

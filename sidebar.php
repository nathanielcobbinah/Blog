<div class="w-full md:w-1/4 lg:w-1/3 space-y-4">
    <!-- Categories -->
    <div class="bg-gray-800 shadow-md rounded-lg p-4">
        <h3 class="text-lg font-semibold mb-4 text-white">Categories</h3>
        <ul class="space-y-3">
            <?php
            if ($result_categories->num_rows > 0) {
                while ($row = $result_categories->fetch_assoc()) {
                    // Check if the category ID matches the active category ID
                    $active_class = ($_GET['category_id'] ?? null) == $row['id'] ? 'text-indigo-400 font-bold' : 'text-gray-300';
                    echo "<li class='flex items-center p-2 bg-gray-700 rounded-lg hover:bg-gray-600 transition'>";
                    if (!empty($row['image'])) {
                        echo "<img src='category_uploads/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "' class='w-8 h-8 mr-3 rounded-full shadow-sm'>";
                    }
                    echo "<a href='blog.php?category_id=" . intval($row['id']) . "' class='$active_class'>" . htmlspecialchars($row['name']) . "</a>";
                    echo "</li>";
                }
            } else {
                echo "<li class='text-gray-300'>No categories found</li>";
            }
            ?>
        </ul>
    </div>

    <!-- Recent Posts -->
    <div class="bg-gray-800 shadow-md rounded-lg p-4">
        <h3 class="text-lg font-semibold mb-4 text-white">Recent Posts</h3>
        <ul class="space-y-3">
            <?php
            $sql_recent_posts = "SELECT * FROM posts ORDER BY created_at DESC LIMIT 3";
            $result_recent_posts = $conn->query($sql_recent_posts);
            if ($result_recent_posts->num_rows > 0) {
                while ($row = $result_recent_posts->fetch_assoc()) {
                    echo "<li class='flex items-center p-2 bg-gray-700 rounded-lg hover:bg-gray-600 transition'>";
                    if (!empty($row['thumbnail_image'])) {
                        echo "<img src='posts_uploads/" . htmlspecialchars($row['thumbnail_image']) . "' alt='Thumbnail' class='w-10 h-10 mr-3 rounded shadow-sm'>";
                    }
                    echo "<a href='post.php?id=" . intval($row['id']) . "' class='text-gray-300 hover:text-indigo-400'>" . htmlspecialchars($row['title']) . "</a>";
                    echo "</li>";
                }
            } else {
                echo "<li class='text-gray-300'>No recent posts found</li>";
            }
            ?>
        </ul>
    </div>
</div>

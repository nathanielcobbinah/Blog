<?php
include 'config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get post ID from URL
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch post details based on ID
$sql_post = "SELECT * FROM posts WHERE id = $post_id";
$result_post = $conn->query($sql_post);

if ($result_post->num_rows == 1) {
    $post = $result_post->fetch_assoc();
    $category_id = $post['category_id'];
} else {
    echo "Post not found.";
    exit();
}

// Fetch categories for the sidebar
$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);

// Pagination settings
$comments_per_page = 5;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $comments_per_page;

// Fetch total number of comments
$sql_count_comments = "SELECT COUNT(*) as total_comments FROM comments WHERE post_id = $post_id";
$result_count_comments = $conn->query($sql_count_comments);
$total_comments = $result_count_comments->fetch_assoc()['total_comments'];
$total_pages = ceil($total_comments / $comments_per_page);

// Fetch comments for the current page
$sql_comments = "SELECT comments.*, users.username, users.profile_image FROM comments LEFT JOIN users ON comments.author_id = users.id WHERE post_id = $post_id ORDER BY created_at DESC LIMIT $comments_per_page OFFSET $offset";
$result_comments = $conn->query($sql_comments);

// Fetch related posts from the same category
$sql_related_posts = "SELECT * FROM posts WHERE category_id = $category_id AND id != $post_id LIMIT 3";
$result_related_posts = $conn->query($sql_related_posts);

// Handle new comment submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'])) {
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit();
    }
    $comment = $conn->real_escape_string($_POST['comment']);
    $author_id = $_SESSION['user']['id'];
    $sql_insert_comment = "INSERT INTO comments (post_id, content, created_at, author_id) VALUES ($post_id, '$comment', NOW(), $author_id)";
    $conn->query($sql_insert_comment);
    header("Location: post.php?id=$post_id&page=$page");
    exit();
}
?>

<?php include 'head.php'; ?>

<div class="container mx-auto px-4 py-8 flex flex-col md:flex-row space-y-8 md:space-y-0 md:space-x-8 bg-gray-900 text-white">
    <!-- Main Content -->
    <div class="flex-1">
        <!-- Main content for post -->
        <div class="md:col-span-3">
            <h2 class="text-2xl mb-4"><?php echo htmlspecialchars($post['title']); ?></h2>
            <?php
            if (!empty($post['thumbnail_image'])) {
                echo "<img src='posts_uploads/" . htmlspecialchars($post['thumbnail_image']) . "' alt='Thumbnail Image' class='w-full h-72 object-cover rounded'>";
            }
            ?>
            <div class="mx-2 mt-4">
                <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
            </div>

            <!-- Comments Section -->
            <div class="mb-8 mt-8">
                <h2 class="text-xl font-semibold mb-4">Comments</h2>
                <?php
                if ($result_comments->num_rows > 0) {
                    while ($comment = $result_comments->fetch_assoc()) {
                        echo "<div class='border rounded-lg p-4 mb-4 bg-gray-800'>";
                        // if (!empty($comment['profile_image'])) {
                        //     echo "<img src='profile_images/" . htmlspecialchars($comment['profile_image']) . "' alt='Profile Image' class='w-8 h-8 rounded-full'>";
                        // }
                        echo "<div class='ml-4'>";
                        echo "<div class='text-gray-400 mb-2 font-semibold'>" . htmlspecialchars($comment['username']) . "</div>";
                        echo "<div class='text-gray-300 mb-2'>" . htmlspecialchars($comment['content']) . "</div>";
                        echo "<div class='text-gray-500 text-sm'>" . htmlspecialchars($comment['created_at']) . "</div>";
                        echo "</div>";
                        echo "</div>";
                    }

                    // Pagination Links
                    echo "<div class='mt-4'>";
                    if ($page > 1) {
                        echo "<a href='post.php?id=$post_id&page=" . ($page - 1) . "' class='text-indigo-400 hover:text-indigo-600'>Previous</a>";
                    }
                    if ($page < $total_pages) {
                        if ($page > 1) echo " | ";
                        echo "<a href='post.php?id=$post_id&page=" . ($page + 1) . "' class='text-indigo-400 hover:text-indigo-600'>Next</a>";
                    }
                    echo "</div>";

                } else {
                    echo "<p>No comments yet. Be the first to comment!</p>";
                }
                ?>

                <!-- Comment Form -->
                <form action="post.php?id=<?php echo $post_id; ?>&page=<?php echo $page; ?>" method="post">
                    <div class="mb-4">
                        <label for="comment" class="block text-gray-400 mb-1">Your Comment:</label>
                        <textarea id="comment" name="comment" rows="3" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-indigo-200 focus:border-indigo-300 bg-gray-800 text-gray-300 placeholder-gray-500" placeholder="Your Comment" required></textarea>
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:bg-indigo-700">Post Comment</button>
                </form>
            </div>

            <!-- Related Posts -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Related Posts</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    if ($result_related_posts->num_rows > 0) {
                        while ($related_post = $result_related_posts->fetch_assoc()) {
                            echo "<div class='bg-gray-800 shadow-md rounded-lg overflow-hidden'>";
                            if (!empty($related_post['thumbnail_image'])) {
                                echo "<img src='posts_uploads/" . htmlspecialchars($related_post['thumbnail_image']) . "' alt='Related Post Image' class='w-full h-48 object-cover'>";
                            }
                            echo "<div class='p-4'>";
                            echo "<h3 class='text-lg font-bold mb-2'>" . htmlspecialchars($related_post['title']) . "</h3
                            ";
                            echo "<p class='text-gray-300'>" . htmlspecialchars($related_post['excerpt']) . "</p>";
                            echo "<a href='post.php?id=" . intval($related_post['id']) . "' class='text-indigo-400 hover:text-indigo-600 mt-4 inline-block'>Read more</a>";
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No related posts found.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
</div>
<?php include 'footer.php'; ?>

<?php $conn->close(); ?>

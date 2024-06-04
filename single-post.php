<?php 
// Include database connection file
require_once 'config.php';

// Initialize variables
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$post = null;
$comments = [];
$related_posts = [];

// Fetch post data
if ($post_id > 0) {
    $sql = "SELECT posts.title, posts.content, posts.excerpt, posts.thumbnail_image, posts.created_at, users.name as author, categories.name as category
            FROM posts
            JOIN users ON posts.user_id = users.id
            JOIN categories ON posts.category_id = categories.id
            WHERE posts.id = ?";
    
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $post = $result->fetch_assoc();
        $stmt->close();
    }

    // Fetch comments
    $sql_comments = "SELECT comments.content, comments.created_at, users.name as author
                     FROM comments
                     JOIN users ON comments.user_id = users.id
                     WHERE comments.post_id = ?";
    
    if ($stmt_comments = $mysqli->prepare($sql_comments)) {
        $stmt_comments->bind_param("i", $post_id);
        $stmt_comments->execute();
        $comments_result = $stmt_comments->get_result();
        while ($comment = $comments_result->fetch_assoc()) {
            $comments[] = $comment;
        }
        $stmt_comments->close();
    }

    // Fetch related posts
    $sql_related = "SELECT id, title, excerpt, thumbnail_image FROM posts WHERE category_id = ? AND id != ? LIMIT 3";
    
    if ($stmt_related = $mysqli->prepare($sql_related)) {
        $stmt_related->bind_param("ii", $post['category_id'], $post_id);
        $stmt_related->execute();
        $related_result = $stmt_related->get_result();
        while ($related_post = $related_result->fetch_assoc()) {
            $related_posts[] = $related_post;
        }
        $stmt_related->close();
    }
}

include 'head.php';
?>

<body class="bg-gray-100">

    <!-- Single Post Content -->
    <div class="container mx-auto px-4 py-8">
        <?php if ($post): ?>
        <!-- Post Title -->
        <h1 class="text-3xl font-bold mb-4"><?php echo htmlspecialchars($post['title']); ?></h1>
        <!-- Post Meta -->
        <div class="flex items-center text-gray-600 mb-4">
            <div class="mr-4">By <?php echo htmlspecialchars($post['author']); ?></div>
            <div class="mr-4"><?php echo date('F j, Y', strtotime($post['created_at'])); ?></div>
            <div class="mr-4">Category: <a href="#" class="text-indigo-600 hover:text-indigo-800"><?php echo htmlspecialchars($post['category']); ?></a></div>
        </div>
        <!-- Post Content -->
        <div class="prose max-w-full mb-8">
            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
        </div>
        <!-- Comments Section -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Comments</h2>
            <?php foreach ($comments as $comment): ?>
            <div class="border rounded-lg p-4 mb-4">
                <div class="text-gray-600 mb-2">By <?php echo htmlspecialchars($comment['author']); ?> on <?php echo date('F j, Y', strtotime($comment['created_at'])); ?></div>
                <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
            </div>
            <?php endforeach; ?>
            <!-- Comment Form (Optional) -->
            <form action="add_comment.php" method="post">
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                <div class="mb-4">
                    <label for="comment" class="block text-gray-600 mb-1">Your Comment:</label>
                    <textarea id="comment" name="comment" rows="3" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-indigo-200 focus:border-indigo-300"></textarea>
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:bg-indigo-700">Post Comment</button>
            </form>
        </div>
        <!-- Related Posts -->
        <div>
            <h2 class="text-xl font-semibold mb-4">Related Posts</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($related_posts as $related_post): ?>
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <?php if (!empty($related_post['thumbnail_image'])): ?>
                    <img src="<?php echo htmlspecialchars($related_post['thumbnail_image']); ?>" alt="Related Post Image" class="w-full h-48 object-cover">
                    <?php endif; ?>
                    <div class="p-4">
                        <h3 class="text-lg font-bold mb-2"><?php echo htmlspecialchars($related_post['title']); ?></h3>
                        <p class="text-gray-700"><?php echo htmlspecialchars($related_post['excerpt']); ?></p>
                        <a href="single-post.php?id=<?php echo $related_post['id']; ?>" class="text-indigo-600 hover:text-indigo-800 mt-4 inline-block">Read more</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php else: ?>
        <p>Post not found.</p>
        <?php endif; ?>
    </div>

    <!-- Sidebar (Same as Homepage Sidebar) -->
    <?php 
      include 'sidebar.php';
    ?>
</body>
</html>

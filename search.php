<?php
include 'config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

$query = isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '';
$search_results = [];

if (!empty($query)) {
    $stmt = $conn->prepare("SELECT * FROM posts WHERE title LIKE ? OR content LIKE ?");
    $search_query = "%" . $query . "%";
    $stmt->bind_param("ss", $search_query, $search_query);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $search_results[] = $row;
    }
    $stmt->close();
}
?>

<!-- Header -->
<?php include 'head.php'; ?>

<div class="container mx-auto px-4 py-8 flex flex-col md:flex-row space-y-8 md:space-y-0 md:space-x-8 bg-gray-900 text-white">
    <h1 class="text-3xl font-bold mb-4 text-white">Search Results for "<?php echo $query; ?>"</h1>
    <?php if (count($search_results) > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php foreach ($search_results as $post): ?>
                <div class="bg-gray-800 text-white shadow-md rounded-lg overflow-hidden">
                    <?php if (!empty($post['thumbnail_image'])): ?>
                        <img src="posts_uploads/<?php echo htmlspecialchars($post['thumbnail_image']); ?>" alt="Post Image" class="w-full h-48 object-cover">
                    <?php endif; ?>
                    <div class="p-4">
                        <h2 class="text-xl font-bold"><?php echo htmlspecialchars($post['title']); ?></h2>
                        <p class="text-gray-400 mt-2"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                        <a href="post.php?id=<?php echo intval($post['id']); ?>" class="text-indigo-400 hover:text-indigo-600 mt-4 inline-block">Read more</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-400">No posts found for your search query.</p>
    <?php endif; ?>
</div>

<!-- Footer -->
<?php include 'footer.php'; ?>

<?php $conn->close(); ?>

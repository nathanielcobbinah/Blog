<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EscadeDev</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white">


<?php
include 'config.php';

// Fetch a random blog post for the hero section
$sql_random_post = "SELECT * FROM posts ORDER BY RAND() LIMIT 1";
$result_random_post = $conn->query($sql_random_post);
$random_post = $result_random_post->fetch_assoc();

// Fetch recent blog posts
$sql_posts = "SELECT * FROM posts ORDER BY created_at DESC LIMIT 2";
$result_posts = $conn->query($sql_posts);

// Fetch categories for the sidebar
$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);
?>


    <!-- Header -->
    <?php $page_title = "EscadeDev"; ?>
    <?php include 'head.php'; ?>

    <!-- Hero Section -->
    <?php if ($random_post): ?>
        <section class="bg-gray-900 text-white py-16">
            <div class="container mx-auto px-4 text-center">
                <?php if (!empty($random_post['thumbnail_image'])): ?>
                    <img src="posts_uploads/<?php echo htmlspecialchars($random_post['thumbnail_image']); ?>" alt="Post Image" class="w-full h-64 object-cover mb-4">
                <?php endif; ?>
                <h1 class="text-4xl md:text-5xl font-bold"><?php echo htmlspecialchars($random_post['title']); ?></h1>
                <p class="mt-4 text-lg md:text-xl"><?php echo htmlspecialchars($random_post['excerpt']); ?></p>
                <a href="post.php?id=<?php echo intval($random_post['id']); ?>" class="mt-8 inline-block bg-white text-gray-900 font-semibold px-6 py-3 rounded-md shadow-md hover:bg-gray-200">Read More</a>
            </div>  
        </section>
    <?php else: ?>
        <section class="bg-gray-900 text-white py-16">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl md:text-5xl font-bold">Welcome to WebDev Blog</h1>
                <p class="mt-4 text-lg md:text-xl">Your ultimate resource for web development tips, tutorials, and news.</p>
                <a href="#" class="mt-8 inline-block bg-white text-gray-900 font-semibold px-6 py-3 rounded-md shadow-md hover:bg-gray-200">Get Started</a>
            </div>  
        </section>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 flex flex-col md:flex-row space-y-8 md:space-y-0 md:space-x-8">
        <!-- Blog Posts -->
        <div class="flex-1">
            <?php
            if ($result_posts->num_rows > 0) {
                while ($row = $result_posts->fetch_assoc()) {
                    echo "<div class='bg-gray-800 text-white shadow-md rounded-lg overflow-hidden mb-6'>";
                    if (!empty($row['thumbnail_image'])) {
                        echo "<img src='posts_uploads/" . htmlspecialchars($row['thumbnail_image']) . "' alt='Post Image' class='w-full h-48 object-cover'>";
                    }
                    echo "<div class='p-4'>";
                    echo "<h2 class='text-xl font-bold'>" . htmlspecialchars($row['title']) . "</h2>";
                    echo "<p class='text-gray-300 mt-2'>" . htmlspecialchars($row['excerpt']) . "</p>";
                    echo "<a href='post.php?id=" . intval($row['id']) . "' class='text-indigo-400 hover:text-indigo-600 mt-4 inline-block'>Read more</a>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>No blog posts found.</p>";
            }
            ?>
        </div>
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>
    
</body>
</html>

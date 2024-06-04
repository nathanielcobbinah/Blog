<?php
include 'config.php';

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user'])) {
    // header("location: login.php");
    // exit();
}

// Check if the post ID is provided in the query string
if (!isset($_GET['id']) || empty(trim($_GET['id']))) {
    // Redirect to manage posts page if ID is not provided
    header("location: admin_manage_posts.php");
    exit();
}

// Fetch categories for the dropdown menu
$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);

// Define variables and initialize with empty values
$title = $content = $excerpt = $thumbnail_image = $category_id = "";
$title_err = $content_err = $category_err = $thumbnail_image_err = "";

// Get the post ID
$post_id = intval($_GET['id']);

// Fetch the post details
$sql_post = "SELECT * FROM posts WHERE id = ?";
if ($stmt = $conn->prepare($sql_post)) {
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $title = $row['title'];
        $content = $row['content'];
        $excerpt = $row['excerpt'];
        $thumbnail_image = $row['thumbnail_image'];
        $category_id = $row['category_id'];
    } else {
        // Redirect to manage posts page if no such post exists
        header("location: admin_manage_posts.php");
        exit();
    }

    $stmt->close();
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate title
    if (empty($_POST["title"])) {
        $title_err = "Please enter a title.";
    } else {
        $title = test_input($_POST["title"]);
    }

    // Validate content
    if (empty($_POST["content"])) {
        $content_err = "Please enter content.";
    } else {
        $content = test_input($_POST["content"]);
    }

    // Validate category
    if (empty($_POST["category_id"])) {
        $category_err = "Please select a category.";
    } else {
        $category_id = test_input($_POST["category_id"]);
    }

    // Validate excerpt (optional)
    $excerpt = test_input($_POST["excerpt"]);

    // Validate thumbnail image
    if (!empty($_FILES["thumbnail_image"]["name"])) {
        $target_dir = "posts_uploads/";
        $target_file = $target_dir . basename($_FILES["thumbnail_image"]["name"]);
        $thumbnail_imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image or fake image
        $check = getimagesize($_FILES["thumbnail_image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $thumbnail_image_err = "File is not an image.";
        }

        // Check file size
        if ($_FILES["thumbnail_image"]["size"] > 5000000) {
            $thumbnail_image_err = "Sorry, your file is too large.";
        }

        // Check if $uploadOk is set to 0 by an error
        if (empty($thumbnail_image_err)) {
            if (move_uploaded_file($_FILES["thumbnail_image"]["tmp_name"], $target_file)) {
                $thumbnail_image = basename($_FILES["thumbnail_image"]["name"]);
            } else {
                $thumbnail_image_err = "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Check for errors before updating in the database
    if (empty($title_err) && empty($content_err) && empty($category_err) && empty($thumbnail_image_err)) {
        // Prepare an update statement
        $sql = "UPDATE posts SET title = ?, content = ?, excerpt = ?, thumbnail_image = ?, category_id = ? WHERE id = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssii", $title, $content, $excerpt, $thumbnail_image, $category_id, $post_id);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to manage posts page
                header("location: admin_manage_posts.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}

// Function to sanitize input data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<?php include 'head.php';?>

<?php  
if (!isset($_SESSION['user'])) {
    header("location: login.php");
    exit();
}
?>
<body class="bg-gray-900 text-white">

    <!-- Header -->
    <?php include 'admin_header.php'; ?>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-2xl mb-4">Edit Post</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $post_id; ?>" method="post" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="title" class="block mb-2">Title:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" class="w-full px-3 py-2 text-gray-800 border rounded <?php echo (!empty($title_err)) ? 'border-red-500' : ''; ?>" required>
                    <span class="text-red-500"><?php echo $title_err; ?></span>
                </div>

                <div class="mb-4">
                    <label for="content" class="block mb-2">Content:</label>
                    <textarea id="content" name="content" class="w-full px-3 py-2 text-gray-800 border rounded <?php echo (!empty($content_err)) ? 'border-red-500' : ''; ?>" required><?php echo htmlspecialchars($content); ?></textarea>
                    <span class="text-red-500"><?php echo $content_err; ?></span>
                </div>

                <div class="mb-4">
                    <label for="excerpt" class="block mb-2">Excerpt:</label>
                    <textarea id="excerpt" name="excerpt" class="w-full px-3 py-2 text-gray-800 border rounded"><?php echo htmlspecialchars($excerpt); ?></textarea>
                </div>

                <!-- Display Thumbnail Image -->
               <div class="mb-4">
                  <label for="thumbnail_image" class="block mb-2">Thumbnail Image:</label>
                  <?php if (!empty($thumbnail_image)): ?>
                     <img src='posts_uploads/<?php echo htmlspecialchars($thumbnail_image); ?>' alt='Thumbnail Image' class='w-72 h-72 object-cover rounded'>
                  <?php endif; ?>
                  <input type="file" id="thumbnail_image" name="thumbnail_image" class="w-full <?php echo (!empty($thumbnail_image_err)) ? 'border-red-500' : ''; ?>">
                  <span class="text-red-500"><?php echo $thumbnail_image_err; ?></span>
               </div>


                <div class="mb-4">
                    <label for="category_id" class="block mb-2">Category:</label>
                    <select id="category_id" name="category_id" class="w-full px-3 py-2 text-gray-800 border rounded <?php echo (!empty($category_err)) ? 'border-red-500' : ''; ?>" required>
                        <option value="">Select Category</option>
                        <?php
                        if ($result_categories->num_rows > 0) {
                            while ($row = $result_categories->fetch_assoc()) {
                                echo "<option value='" . $row["id"] . "'" . ($row["id"] == $category_id ? " selected" : "") . ">" . $row["name"] . "</option>";
                            }
                        } else {
                            echo "<option value='' disabled>No categories found</option>";
                        }
                        ?>
                    </select>
                    <span class="text-red-500"><?php echo $category_err; ?></span>
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
</html>

<?php
include 'config.php';


if (!isset($_SESSION['user'])) {
    header("location: login.php");
    exit();
}

// Fetch categories from database
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);

// Define variables and initialize with empty values
$name = $description = "";
$name_err = $description_err = $image_err = "";

// Define function to check for HTML injection
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty($_POST["name"])) {
        $name_err = "Please enter a name.";
    } else {
        $name = test_input($_POST["name"]);
    }

    // Validate description
    $description = test_input($_POST["description"]);
}
?>

<?php $page_title = "Add category"; ?>
<?php include 'head.php'; ?>

<div class="container mx-auto px-4 py-8 flex flex-col md:flex-row space-y-8 md:space-y-0 md:space-x-8">
    <!-- Main Content -->
    <div class="flex-1">
        <div class="md:col-span-3">
            <h2 class="text-2xl mb-4 text-white">Add Category</h2>
            <form action="add_category.php" method="post" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="name" class="block mb-2 text-white">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo $name; ?>" class="w-full px-3 py-2 border rounded <?php echo (!empty($name_err)) ? 'border-red-500' : ''; ?>" required>
                    <span class="text-red-500"><?php echo $name_err; ?></span>
                </div>

                <div class="mb-4">
                    <label for="description" class="block mb-2 text-white">Description:</label>
                    <textarea id="description" name="description" class="w-full px-3 py-2 border rounded <?php echo (!empty($description_err)) ? 'border-red-500' : ''; ?>"><?php echo $description; ?></textarea>
                    <span class="text-red-500"><?php echo $description_err; ?></span>
                </div>

                <div class="mb-4">
                    <label for="image" class="block mb-2 text-white">Image:</label>
                    <input type="file" id="image" name="image" class="w-full <?php echo (!empty($image_err)) ? 'border-red-500' : ''; ?>">
                    <span class="text-red-500"><?php echo $image_err; ?></span>
                </div>

                <div>
                    <input type="submit" value="Submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                </div>
            </form>
        </div>
    </div>
    <div class="w-full md:w-1/4 lg:w-1/3 space-y-4">
        <ul class="bg-gray-800 text-white shadow-md rounded-lg p-4">
            <h2 class="text-2xl mt-8">Categories</h2>
            <?php
            // Display categories
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<li><a href='blog.php?category_id=" . $row['id'] . "' class='text-gray-300 hover:text-gray-400'>" . $row['name'] . "</a></li>";
                }
            } else {
                echo "<li class='text-gray-300'>0 categories found.</li>";
            }
            ?>
        </ul>        
    </div>
</div>

<?php
// Include database connection file
require_once 'config.php';

// Initialize variables
$comment = "";
$comment_err = "";

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate comment
    if (empty(trim($_POST["comment"]))) {
        $comment_err = "Please enter a comment.";
    } else {
        $comment = trim($_POST["comment"]);
    }

    // Check input errors before inserting in database
    if (empty($comment_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO comments (post_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("iis", $param_post_id, $param_user_id, $param_content);

            // Set parameters
            $param_post_id = $_POST['post_id'];
            $param_user_id = 1; // Assuming a logged-in user with user_id = 1. Adjust accordingly.
            $param_content = $comment;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to the post page
                header("location: single-post.php?id=" . $param_post_id);
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $mysqli->close();
}
?>

<?php
include 'config.php';
session_start();

// Function to sanitize user input
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    $confirm_password = sanitizeInput($_POST['confirm_password']);
    $profile_image = $_FILES['profile_image'];

    // Validate form data
    $errors = [];
    if (empty($username)) {
        $errors['username'] = "Username is required";
    }
    if (empty($email)) {
        $errors['email'] = "Email is required";
    }
    if (empty($password)) {
        $errors['password'] = "Password is required";
    }
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "Passwords do not match";
    }
    if ($profile_image['error'] !== UPLOAD_ERR_OK) {
        $errors['profile_image'] = "Profile image is required";
    }

    // If there are errors, redirect back to the signup form
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $_POST;
        header("Location: signup.php");
        exit();
    }

    // If no errors, proceed to store user data
    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Handle profile image upload
        $target_dir = "profile_images/";
        $target_file = $target_dir . basename($profile_image["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image or fake image
        $check = getimagesize($profile_image["tmp_name"]);
        if ($check === false) {
            die("File is not an image.");
        }

        // Check file size (5MB max)
        if ($profile_image["size"] > 5000000) {
            die("Sorry, your file is too large.");
        }

        // Allow certain file formats
        $allowed_formats = ['jpg', 'png', 'jpeg', 'gif'];
        if (!in_array($imageFileType, $allowed_formats)) {
            die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        }

        if (move_uploaded_file($profile_image["tmp_name"], $target_file)) {
            // Insert data into database
            $sql = "INSERT INTO users (username, email, password, profile_image) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Error preparing statement: " . $conn->error);
            }

            $stmt->bind_param("ssss", $username, $email, $hashed_password, $target_file);
            if ($stmt->execute()) {
                // Set session variables to log in the user automatically
                $_SESSION['user'] = [
                    'id' => $stmt->insert_id,
                    'username' => $username,
                    'email' => $email,
                    'profile_image' => $target_file
                ];

                // Redirect to index page
                header("Location: index.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            die("Sorry, there was an error uploading your file.");
        }
    }

    $conn->close();
}
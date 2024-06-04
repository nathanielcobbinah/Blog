<?php
include 'config.php';
session_start();

// Function to sanitize user input
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);

    // Validate form data
    $errors = [];
    if (empty($email)) {
        $errors['email'] = "Email is required";
    }
    if (empty($password)) {
        $errors['password'] = "Password is required";
    }

    if (empty($errors)) {
        $sql = "SELECT id, username, password, profile_image FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $hashed_password, $profile_image);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user'] = [
                    'id' => $id,
                    'username' => $username,
                    'profile_image' => $profile_image,
                ];
                header("Location: index.php");
                exit;
            } else {
                $errors['password'] = "Invalid password";
            }
        } else {
            $errors['email'] = "No account found with that email";
        }
        $stmt->close();
    }

    $_SESSION['errors'] = $errors;
    header("Location: login.php");
    exit;
}

$conn->close();
<?php
include 'config.php';

// Check if an admin already exists
$sql = "SELECT * FROM users WHERE role = 'admin'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    // No admin exists, create one
    $username = 'admin';
    $email = 'admin@example.com';
    $password = password_hash('admin123', PASSWORD_DEFAULT); // Securely hash the password
    $role = 'admin';

    $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $username, $email, $password, $role);
        if ($stmt->execute()) {
            echo "Admin user created successfully.";
        } else {
            echo "Error: Could not execute query: $sql. " . $conn->error;
        }
    } else {
        echo "Error: Could not prepare query: $sql. " . $conn->error;
    }
} else {
    echo "Admin user already exists.";
}

$conn->close();
?>

<?php
include 'config.php';

// Define variables and initialize with empty values
$name = $description = $image = "";
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

    // Validate image
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "category_uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $image_err = "File is not an image.";
        }

        // Check file size
        if ($_FILES["image"]["size"] > 500000) {
            $image_err = "Sorry, your file is too large.";
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $image_err = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }

    // Check input errors before inserting into database
    if (empty($name_err) && empty($description_err) && empty($image_err)) {
        // Upload image if valid
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = basename($_FILES["image"]["name"]);
        } else {
            $image_err = "Sorry, there was an error uploading your file.";
        }

        // Insert data into database
        $sql = "INSERT INTO categories (name, description, image) VALUES ('$name', '$description', '$image')";
        if ($conn->query($sql) === TRUE) {
            // Redirect back to the form page
            header("Location: add_category_form.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // Redirect back to the form page with validation errors
        header("Location: add_category_form.php?name_err=$name_err&description_err=$description_err&image_err=$image_err");
        exit();
    }
}
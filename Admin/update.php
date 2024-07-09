<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "idgenerate";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

$errorMessage = "";
$successMessage = "";

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST["id"];
    $newId = $_POST["newId"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];

    // Validate input
    if (empty($id) || empty($newId) || empty($name) || empty($email) || empty($phone) || empty($address)) {
        $errorMessage = "All fields are required";
    } else {
        // Prepare the SQL statement
        $sql = "UPDATE examinees SET id=?, name=?, email=?, phone=?, address=? WHERE id=?";

        // Prepare and bind parameters to avoid SQL injection
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("issssi", $newId, $name, $email, $phone, $address, $id);

        // Execute the statement
        if ($stmt->execute()) {
            $successMessage = "Examinee updated correctly";
        } else {
            $errorMessage = "Error updating examinee: " . $connection->error;
        }

        $stmt->close();
    }
} else {
    $errorMessage = "Invalid request method";
}

$connection->close();

// Error message
if (!empty($errorMessage)) {
    header("Location: /jazzphp/Admin/table.php?error=" . urlencode($errorMessage));
    exit();
}

// Success message
if (!empty($successMessage)) {
    header("Location: /jazzphp/Admin/table.php?success=" . urlencode($successMessage));
    exit();
}

// Check if email contains "@" symbol
if (!strpos($email, '@')) {
    $errorMessage = "Email address is invalid. Please enter a valid email address.";
} else {
    header("Location: /jazzphp/Admin/table.php?success=" . urlencode($successMessage));
}

?>
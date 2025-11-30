<?php
$host = 'localhost'; 
$user = 'root';
$password = '';
$dbname = 'GroceryShop';

// Create a connection
$conn = mysqli_connect($host, $user, $password, $dbname);
if (mysqli_connect_errno()) {
    die('Connection failed due to: ' . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get form data
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm-password']);

    // Validate inputs
    if (empty($fullname) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "Please fill in all fields.";
    } elseif ($password !== $confirm_password) {
        echo "Passwords do not match.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if the email is already registered
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo "Email already registered.";
        } else {
            // Insert the new user into the database
            $sql = "INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $fullname, $email, $hashed_password);

            if (mysqli_stmt_execute($stmt)) {
                echo "Registration successful!";
            } else {
                echo "Something went wrong. Please try again.";
            }
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    }
}

// Close the connection
mysqli_close($conn);
?>

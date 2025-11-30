<?php
session_start();

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

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo "Please fill in all fields.";
    } else {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['email'] = $user['email'];

                header("Location: dashboard.php");
                exit();
            } else {
                echo "Incorrect password.";
            }
        } else {
            echo "No user found with this email.";
        }

        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);
?>

<?php
function registerUser($username, $password) {
    include_once("koneksi.php"); // Include the database connection code

    // Sanitize user input to prevent SQL injection
    $username = mysqli_real_escape_string($connection, $username);
    // You should also hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert the user's registration data into the 'user' table
    $sql = "INSERT INTO user (username, password) VALUES ('$username', '$hashedPassword')";

    if (mysqli_query($connection, $sql)) {
        return true; // Registration successful
    } else {
        return false; // Registration failed
    }
}

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (registerUser($username, $password)) {
        echo "Registration successful!";
    } else {
        echo "Registration failed. Please try again.";
    }
}
?>

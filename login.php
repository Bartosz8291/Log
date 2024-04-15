<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate form data (you can add more validation rules as needed)
    if (empty($username) || empty($password)) {
        echo "Username and password are required.";
    } else {
        // Connect to MySQL database (replace placeholders with your actual database credentials)
        $servername = "localhost";
        $db_username = "your_username";
        $db_password = "your_password";
        $dbname = "your_database";

        $conn = new mysqli($servername, $db_username, $db_password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare SQL statement to retrieve user data from the database
        $stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);

        // Execute the prepared statement
        $stmt->execute();

        // Bind the result variables
        $stmt->bind_result($db_username, $db_password_hash);

        // Fetch the result
        if ($stmt->fetch()) {
            // Verify password
            if (password_verify($password, $db_password_hash)) {
                // Password is correct, start a new session
                $_SESSION["username"] = $username;
                echo "Login successful. Welcome, $username!";
                // Redirect to the user's dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Incorrect password.";
            }
        } else {
            echo "User not found.";
        }

        // Close statement and database connection
        $stmt->close();
        $conn->close();
    }
} else {
    // Redirect back to the login page if accessed directly
    header("Location: login.html");
    exit();
}
?>

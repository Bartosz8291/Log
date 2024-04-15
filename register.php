<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST["new_username"];
    $password = $_POST["new_password"];

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

        // Prepare SQL statement to insert user data into the database
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password_hash);

        // Hash the password before storing it in the database (you should never store passwords in plain text)
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Execute the prepared statement
        if ($stmt->execute()) {
            echo "Registration successful.";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement and database connection
        $stmt->close();
        $conn->close();
    }
} else {
    // Redirect back to the registration page if accessed directly
    header("Location: register.html");
    exit();
}
?>

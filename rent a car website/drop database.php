<?php

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "my_rent_buddy";

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Drop the database
    $sql = "DROP DATABASE IF EXISTS $database";
    if ($conn->query($sql) === TRUE) {
        echo "Database deleted successfully\n";
    } else {
        throw new Exception("Error deleting database: " . $conn->error);
    }

    // Close the connection
    $conn->close();
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}

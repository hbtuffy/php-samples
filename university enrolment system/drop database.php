<?php

// Establish database connection
$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "music_conservatorium";

// Check if connection is successful
try {
    $db_conn = mysqli_connect($db_host, $db_user, $db_password);
    echo "Connection is successful.<br/>";
    echo "You are connected $db_name.<br/>";
} catch (mysqli_sql_exception $e) {
    die("Connection failed: " . mysqli_connect_errno() . "-" . mysqli_connect_error() . "<br/>");
}

$sql_drop_database = "DROP DATABASE $db_name";

try {
    mysqli_query($db_conn, $sql_drop_database);
    echo "$db_name has been successfully dropped!<br/>";
} catch (mysqli_sql_exception $e) {
    die("DROP DATABASE failed: " . mysqli_connect_errno() . "-" . mysqli_connect_error() . "<br/>");
}


// Close database connection
mysqli_close($db_conn);
echo "Disconnection is successful.<br/>";
echo "You are disconnected from $db_name.<br/>";

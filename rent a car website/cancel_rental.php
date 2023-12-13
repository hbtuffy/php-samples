<?php
session_start();

$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "my_rent_buddy";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["car_no"])) {
        $carNo = $_POST["car_no"];

        try {
            $db_connection = mysqli_connect($db_host, $db_user, $db_password);
            mysqli_select_db($db_connection, $db_name);
            $table_rented_cars = "rented_car";

            // Delete the car from the rented_car table
            $deleteQuery = "DELETE FROM $table_rented_cars WHERE car_no = $carNo";
            mysqli_query($db_connection, $deleteQuery);

            mysqli_close($db_connection);

            // Redirect back to the previous page
            header("Location: admin available cars.php");
            exit();
        } catch (mysqli_sql_exception $e) {
            die("<p>Error in connection with the database server or database </p>\n" . mysqli_error($db_connection));
        }
    }
}

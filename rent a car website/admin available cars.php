<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Portal</title>
</head>
<?php
include("header.php")
?>

<body>
    <h1>Admin Portal</h1>
    <h2>Inventory Controller</h2>

    <?php
    //Portal control buttons
    echo "<table>";
    echo "<tr>";
    echo "<td>";
    echo "<form method='post' " . " action='admin available cars.php?" . SID . "'>";
    echo "<input type='submit' name='submit' " . " value='View Car Inventory'>";
    echo "</form>";
    echo "</td>";
    echo "<td>";
    echo "<form method='post' " . " action='insert a car.php?" . SID . "'>";
    echo "<input type='submit' name='submit' " . " value='Insert a New Car'>";
    echo "</form>";
    echo "</td>";
    echo "</tr>";
    echo "</table>";

    echo "<table>";
    echo "<form method='post' " . " action='admin car detail.php?" . SID . "'>";
    echo "<tr><td>Plate number: </td><td><input type='text' name='rego'> </input></td></tr>";
    echo "<tr><td>Model: </td><td><input type='text' name='model'> </input></td></tr>";
    echo "<tr><td>Type: </td><td><input type='text' name='type'> </input></td></tr>";
    echo "<tr><td></td><td><input type='submit' name='action' value='Search' /></td></tr>";
    echo "</form>";
    echo "</td>";
    echo "</tr>";

    echo "</table>";
    ?>

    <?php
    //Database login details
    $db_host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "my_rent_buddy";

    try {
        $db_connection = mysqli_connect($db_host, $db_user, $db_password);
        mysqli_select_db($db_connection, $db_name);
        //Get table names
        $table_available_cars = "available_car";
        $table_rented_cars = "rented_car";
        $table_list_off_cars = "list_off_cars";

        // Get all cars from the available_car table
        $query = "SELECT * FROM $table_available_cars";
        $result = mysqli_query($db_connection, $query);

        if (mysqli_num_rows($result) > 0) {
            //Created table and its header
            echo "<table style='border: 1px solid #3c284a;border-collapse: collapse;width:100%'>\n";
            echo "<tr>\n";
            echo "<th style='background-color:#3c284a;color:white;border: 1px solid #3c284a;'>car_no</th>\n";
            echo "<th style='background-color:#3c284a;color:white;border: 1px solid #3c284a;'>plates</th>\n";
            echo "<th style='background-color:#3c284a;color:white;border: 1px solid #3c284a;'>model</th>\n";
            echo "<th style='background-color:#3c284a;color:white;border: 1px solid #3c284a;'>status</th>\n";
            echo "<th style='background-color:#3c284a;color:white;border: 1px solid #3c284a;'>cost_per_day</th>\n";
            echo "<th style='background-color:#3c284a;color:white;border: 1px solid #3c284a;'>type</th>\n";
            echo "<th style='background-color:#3c284a;color:white;border: 1px solid #3c284a;'>Action</th>\n";
            echo "</tr>\n";

            while ($row = mysqli_fetch_assoc($result)) {
                $carNo = $row['car_no'];

                // Check if the car is rented
                $rentedQuery = "SELECT * FROM $table_rented_cars WHERE car_no=$carNo";
                $rentedResult = mysqli_query($db_connection, $rentedQuery);
                // Check if the car is listed off
                $checkQuery = "SELECT * FROM $table_list_off_cars WHERE car_no = '$carNo'";
                $checkResult = mysqli_query($db_connection, $checkQuery);

                echo "<tr>\n";
                echo "<td style='border: 1px solid #3c284a;'>" . htmlentities($row['car_no']) . "</td>\n";
                echo "<td style='border: 1px solid #3c284a;'>" . htmlentities($row['plates']) . "</td>\n";
                echo "<td style='border: 1px solid #3c284a;'>" . htmlentities($row['model']) . "</td>\n";
                echo "<td style='border: 1px solid #3c284a;'>";

                if (mysqli_num_rows($rentedResult) > 0) {
                    // Car is rented
                    echo "Currently rented";
                    echo "</td>\n";
                    echo "<td style='border: 1px solid #3c284a;'>" . htmlentities($row['cost_per_day']) . "</td>\n";
                    echo "<td style='border: 1px solid #3c284a;'>" . htmlentities($row['type']) . "</td>\n";
                    echo "<td style='border: 1px solid #3c284a;'>";
                    echo "<form method='post' action='cancel_rental.php'>";
                    echo "<input type='hidden' name='car_no' value='" . $row['car_no'] . "'>";
                    echo "<input type='submit' name='submit' value='Cancel Rental'>";
                    echo "</form>";
                    echo "</td>\n";
                } elseif (mysqli_num_rows($checkResult) > 0) {
                    // Car is listed off
                    echo "Listed Off";
                    echo "</td>\n";
                    echo "<td style='border: 1px solid #3c284a;'>" . htmlentities($row['cost_per_day']) . "</td>\n";
                    echo "<td style='border: 1px solid #3c284a;'>" . htmlentities($row['type']) . "</td>\n";
                    echo "<td style='border: 1px solid #3c284a;'>";
                    echo "<form method='post' action='on_list.php'>";
                    echo "<input type='hidden' name='car_no' value='" . $row['car_no'] . "'>";
                    echo "<input type='submit' name='submit' value='List On'>";
                    echo "</form>";
                    echo "</td>\n";
                } else {
                    // Car is available
                    echo "Available";
                    echo "</td>\n";
                    echo "<td style='border: 1px solid #3c284a;'>" . htmlentities($row['cost_per_day']) . "</td>\n";
                    echo "<td style='border: 1px solid #3c284a;'>" . htmlentities($row['type']) . "</td>\n";
                    echo "<td style='border: 1px solid #3c284a;'>";
                    echo "<form method='post' action='list_off.php'>";
                    echo "<input type='hidden' name='car_no' value='" . $row['car_no'] . "'>";
                    echo "<input type='submit' name='submit' value='List Off'>";
                    echo "</form>";
                    echo "</td>\n";
                }
                echo "</tr>\n";
            }
            echo "</table>\n";
        } else {
            echo "<p>No cars available.</p>";
        }
        mysqli_close($db_connection);
    } catch (mysqli_sql_exception $e) {
        die("<p>Error in connection with the database server or database </p>\n" . mysqli_error($db_connection));
    }
    ?>

    <p><a href='registraion login page.php'>Log Out</a></p>

    <?php
    include("footer.php")
    ?>
</body>

</html>
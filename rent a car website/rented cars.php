<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>User Portal</title>
</head>
<?php
include("header.php")
?>

<body>

    <h1>User Portal</h1>
    <h2>Rented Cars</h2>
    <?php
    echo "<table>";
    echo "<tr>";
    echo "<td>";
    echo "<form method='post' " . " action='available cars.php?" . SID . "'>";
    echo "<input type='submit' name='submit' " . " value='View available cars'>";
    echo "</form>";
    echo "</td>";
    echo "<td>";
    echo "<form method='post' " . " action='rented cars.php?" . SID . "'>";
    echo "<input type='submit' name='submit' " . " value='Your Rented Cars'>";
    echo "</form>";
    echo "</td>";
    echo "<td>";
    echo "<form method='post' " . " action='previously_rented.php?" . SID . "'>";
    echo "<input type='submit' name='submit' " . " value='Previous Rented'>";
    echo "</form>";
    echo "</td>";
    echo "</tr>";
    echo "</table>";

    ?>
    <?php
    //Database login information
    $db_host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "my_rent_buddy";

    // if (isset($_COOKIE['LastRequestDate'])) {
    //     $LastRequestDate = $_COOKIE['LastRequestDate'];
    // } else {
    //     $LastRequestDate = "";
    // }

    try {
        $db_connection = mysqli_connect($db_host, $db_user, $db_password);
        mysqli_select_db($db_connection, $db_name);
        $table_user = "user";
        $select_table = "SELECT * FROM $table_user WHERE user_id='" . $_SESSION['user_id'] . "'";
        $qRes = mysqli_query($db_connection, $select_table);
        if (mysqli_num_rows($qRes) == 0) {
            die("<p>Invalid user ID!</p>");
        }
        // $Row = mysqli_fetch_assoc($qRes);
        // $student_name = $Row['first_name'] . " " . $Row['last_name'];

        $table_rented_cars = "rented_car";
        // $approved_enrolments = 0;
        // $count_car_id = "SELECT COUNT(id) FROM $table_rented_cars WHERE user_id='" . $_SESSION["user_id"] . "' AND date_approved IS NOT NULL";
        // $qRes = mysqli_query($db_connection, $count_car_id);
        // if (mysqli_num_rows($qRes) > 0) {
        //     $Row = mysqli_fetch_row($qRes);
        //     $approved_enrolments = $Row[0];
        // }

        $selected_cars = array();
        $select_cars = "SELECT car_no FROM $table_rented_cars WHERE user_id='" . $_SESSION["user_id"] . "'";
        $qRes = mysqli_query($db_connection, $select_cars);
        if (mysqli_num_rows($qRes) > 0) {
            while (($Row = mysqli_fetch_row($qRes)) != FALSE) {
                $selected_cars[] = $Row[0];
            }
        }

        $rented_cars = array();
        $select_rented_id = "SELECT id FROM $table_rented_cars WHERE date_approved IS NOT NULL";
        $qRes = mysqli_query($db_connection, $select_rented_id);
        if (mysqli_num_rows($qRes) > 0) {
            while (($Row = mysqli_fetch_row($qRes)) != FALSE) {
                $rented_cars[] = $Row[0];
            }
        }

        $table_available_cars = "available_car";
        $cars = array();
        $select_cars_columns = "SELECT ac.car_no, ac.plates, ac.model, ac.type, ac.status, rc.total_cost
                        FROM $table_available_cars AS ac
                        JOIN $table_rented_cars AS rc ON ac.car_no = rc.car_no";
        $qRes = mysqli_query($db_connection, $select_cars_columns);
        if (mysqli_num_rows($qRes) > 0) {
            while (($Row = mysqli_fetch_assoc($qRes)) != FALSE) {
                $cars[] = $Row;
            }
        }
        //mysqli_close($db_connection);
    } catch (mysqli_sql_exception $e) {
        die("<p>Error in connection with the database server or database </p>\n" . mysqli_error($db_connection));
    }
    // if (!empty($LastRequestDate)) {
    //     echo "<p>You last requested enrollment on " . $LastRequestDate . ".</p>\n";
    // }


    echo "<table style='border: 1px solid #3c284a;border-collapse: collapse;width:100%'>\n";
    echo "<tr>\n";
    echo " <th style='background-color:#3c284a;color:white;border: 1px solid #3c284a;'>Car#</th>\n";
    echo " <th style='background-color:#3c284a;color:white;border: 1px solid #3c284a;'>Rego#</th>\n";
    echo " <th style='background-color:#3c284a;color:white;border: 1px solid #3c284a;'>Model</th>\n";
    echo " <th style='background-color:#3c284a;color:white;border: 1px solid #3c284a;'>Type</th>\n";
    echo " <th style='background-color:#3c284a;color:white;border: 1px solid #3c284a;'>Cost AUD$</th>\n";
    echo " <th style='background-color:#3c284a;color:white;border: 1px solid #3c284a;'>Action</th>\n";
    echo "</tr>\n";
    foreach ($cars as $car) {
        if (!in_array($car['car_no'], $selected_cars)) {
            continue;
        };
        if (!in_array($car['car_no'], $rented_cars)) {
            //if (in_array($car['car_no'], $selected_cars)){continue;}
            echo "<tr>\n";
            echo " <td style='border: 1px solid #3c284a;'>" . htmlentities($car['car_no']) . "</td>\n";
            echo " <td style='border: 1px solid #3c284a;'>" . htmlentities($car['plates']) . "</td>\n";
            echo " <td style='border: 1px solid #3c284a;'>" . htmlentities($car['model']) . "</td>\n";
            echo " <td style='border: 1px solid #3c284a;'>" . htmlentities($car['type']) . "</td>\n";
            echo " <td style='border: 1px solid #3c284a;'>" . htmlentities($car['total_cost']) . "$</td>\n";
            echo " <td style='border: 1px solid #3c284a;'>";
            if (in_array($car['car_no'], $selected_cars)) {
                echo "<a href='return_a_car.php?" . SID . "&car_no=" . $car['car_no']  . "&total_cost=" . $car['total_cost'] . "'>Return</a>";
            }
            // else {
            //     if ($approved_enrolments > 0) {
            //         echo "Open";
            //     } else {
            //         echo "<a href='renting car.php?" . SID . "&car_no=" . $car['car_no'] . "&cost_per_day=" . $car['cost_per_day'] . "'>Rent</a>";
            //     }
            // }
            echo "</td>\n";
            echo "</tr>\n";
        }
    }

    //     else {
    //     echo "Thank you for checking your profile. We regret to inform you that you currently do not have any active rental cars in your inventory. Please review the available cars listed.";
    // };

    echo "</table>\n";
    echo "<p><a href='registraion login page.php'>Log Out</a></p>";
    ?>
    <?php
    include("footer.php")
    ?>
</body>

</html>
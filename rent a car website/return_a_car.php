<?php
session_start();
$Body = "";
$errors = 0;
$user_id = 0;

$errors = 0;
$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "my_rent_buddy";

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $Body .= "<p>You have not selected a car. Please return to the <a href='available cars.php?" . SID . "'>Available Opportunities page</a>.</p>";
    ++$errors;
}
if ($errors == 0) {
    if (isset($_GET['car_no'])) {
        $car_no = $_GET['car_no'];
    } else {
        $Body .= "<p>You have not selected a car. Please return to the <a href='available cars.php?" . SID . "'>Available Opportunities page</a>.</p>";

        ++$errors;
    }
}
if ($errors == 0) {
    if (isset($_GET['total_cost'])) {
        $total_cost = $_GET['total_cost'];
    } else {
        $Body .= "<p>This car does not have a cost. It is unable to return. Please contact your administrator.</p>";
        ++$errors;
    }
}
if ($errors == 0) {
    try {
        $db_connection = mysqli_connect($db_host, $db_user, $db_password);
        $result = mysqli_select_db($db_connection, $db_name);
        $display_data = date("Y-m-d");
        $db_date = date("Y-m-d H:i:s");
        $table_rented_car = "rented_car";
        $table_previously_rented = "previously_rented";

        // Get the details of the car being deleted
        $select_car_query = "SELECT * FROM $table_rented_car WHERE car_no=$car_no AND user_id=$user_id";
        $car_result = mysqli_query($db_connection, $select_car_query);
        $car_data = mysqli_fetch_assoc($car_result);

        // Insert the car into the previously_rented table
        $insert_previously_rented = "INSERT INTO $table_previously_rented (user_id, car_no, start_date, end_date, total_cost) VALUES ($user_id, $car_no, '{$car_data['start_date']}', '{$car_data['end_date']}', {$car_data['total_cost']})";
        mysqli_query($db_connection, $insert_previously_rented);
        // $insert_enrolments = "INSERT INTO $table_enrolments (class_id,student_id,date_selected) VALUES ($class_id, $student_id, '$db_date')";
        $insert_rented_car = "DELETE FROM $table_rented_car WHERE car_no=$car_no AND user_id=$user_id";
        $qRes = mysqli_query($db_connection, $insert_rented_car);
        $Body .= "<p>Your car# " . " $car_no has been removed on $display_data. Your total cost is $total_cost $</p>\n";
        mysqli_close($db_connection);
    } catch (mysqli_sql_exception $e) {
        $Body .= "<p>Unable to execute the query.</p>\n";
        ++$errors;
    }
}
// if ($user_id > 0)
//     $Body .= "<p>Return to the <a href='available classes.php?" . SID . "'>Available classes</a> page.</p>\n";
// else
//     $Body .= "<p>Please <a href='student login.php'>Register or Log In</a> to use this page.</p>\n";

// if ($errors == 0)
//     setcookie("LastRequestDate", urlencode($display_data), time() + 60 * 60 * 24 * 7);

?>
<!DOCTYPE html>
<html>

<head>
    <title>User Portal</title>
</head>

<body>
    <?php
    include("header.php")
    ?>

    <h1>User Portal</h1>
    <h2>Return a Car</h2>
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
    echo "<br>";

    ?>
    <?php
    echo $Body;
    ?>
    <?php
    include("footer.php")
    ?>
</body>

</html>
<?php
session_start();
$Body = "";
$errors = 0;
$InternID = 0;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $Body .= "<p>You have not logged in or registered. Please return to the <a href='InternLogin.php'>Registration / Log In page</a>.</p>";
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
    if (isset($_GET['cost_per_day'])) {
        $cost_per_day = $_GET['cost_per_day'];
    } else {
        $Body .= "<p>This car does not have a cost. It is unable to return. Please contact your administrator.</p>";
        ++$errors;
    }
}
if ($errors == 0) {
    try {
        $conn = mysqli_connect("localhost", "root", "");
        $DBName = "my_rent_buddy";
        $result = mysqli_select_db($conn, $DBName);
        $DisplayDate = date("Y-m-d");

        if (isset($_GET["renting_a_car"]) && isset($_GET['submitted'])) {
            // Form submitted, process the data
            if (isset($_GET["pickup_date"]) && isset($_GET["return_date"]) && $DisplayDate <= $_GET["pickup_date"] && $_GET["return_date"] >= $_GET["pickup_date"]) {
                $pickup_date = $_GET["pickup_date"];
                $return_date = $_GET["return_date"];
                $from_date = strtotime($pickup_date); // Convert to timestamp
                $to_date = strtotime($return_date); // Convert to timestamp
                $diff_in_seconds = $to_date - $from_date; // Calculate the difference in seconds
                $days = ceil($diff_in_seconds / (60 * 60 * 24)); // Convert seconds to days and round up

                // Calculate the total cost
                //$total_cost = $cost_per_day * $days;
                if ($days == 0) {
                    $total_cost = $cost_per_day / 2;
                } else {
                    $total_cost = $cost_per_day * $days;
                }

                $TableName = "rented_car";
                $sql = "INSERT INTO $TableName (car_no, user_id, start_date, end_date,total_cost) VALUES ($car_no, $user_id, '$pickup_date', '$return_date', $total_cost)";
                $qRes = mysqli_query($conn, $sql);
                $Body .= "<p>Your car no #$car_no has been entered on $DisplayDate.</p>\n";
                if ($days == 0) {
                    $Body .= "<p>You rent for same day rental. Your total cost with %50 discount will be $total_cost$</p>\n";
                } else {
                    $Body .= "<p>Your total cost for $days day(s) rental will be $total_cost$</p>\n";
                }

                $Body .= "<p>Your booked the car from $pickup_date to $return_date</p>\n";
                $_GET['submitted'] = true;
            } else {
                $Body .= "<p>Please enter available Pick-up Date and Return Date.</p>\n";
                ++$errors;
            }
        }
        mysqli_close($conn);
    } catch (mysqli_sql_exception $e) {
        $Body .= "<p>Unable to execute the query.</p>\n";
        ++$errors;
    }
}

// if (isset($_GET['submitted'])) {
//     $Body .= "<p>Your request has been submitted.</p>\n";
//     $Body .= "<p>Return to the <a href='available cars.php?" . SID . "'>Available Opportunities</a> page.</p>\n";
//     $_GET['submitted'] = false;
// } else {
//     $Body .= "<p>Please <a href='InternLogin.php'>Register or Log In</a> to use this page.</p>\n";
// }

if ($errors == 0) {
    setcookie("LastRequestDate", urlencode($DisplayDate), time() + 60 * 60 * 24 * 7); //, "/examples/internship/");
}
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
    <h2>Renting a Car</h2>
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
    <?php if (!isset($_GET['submitted'])) : ?>
        <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
            <input type="hidden" name="car_no" value="<?php echo $car_no; ?>">
            <input type="hidden" name="cost_per_day" value="<?php echo $cost_per_day; ?>">
            Pick up date: <input type="date" name="pickup_date" /><br><br>
            Drop off date: <input type="date" name="return_date" /><br>
            <input type="hidden" name="submitted" value="true"><br>
            <button type="submit" name="renting_a_car">Rent</button>
        </form>
    <?php endif; ?>
    <?php echo $Body; ?>
    <?php
    include("footer.php")
    ?>
</body>

</html>
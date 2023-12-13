<?php
session_start();
$body = "";
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
</head>
<?php
include("header.php");
//$body = ""
?>

<body>
    <h1>Admin/User Registration</h1>
    <?php
    $errors = 0;
    $db_host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "my_rent_buddy";
    $table_name = "user";
    $user_type = "x";

    try {
        $db_connection = mysqli_connect($db_host, $db_user, $db_password, $db_name);
        $select_user = "SELECT * FROM $table_name" . " where email='" . stripslashes($_POST['email']) . "' and password='" . md5(stripslashes($_POST['password'])) . "'";
        $qRes = mysqli_query($db_connection, $select_user);

        if (mysqli_num_rows($qRes) == 0) {
            echo "<p>The e-mail address/password " . " combination entered is not valid. </p>\n";
            ++$errors;
        } else {
            // Fetch the information from the query
            $row = mysqli_fetch_assoc($qRes);
            $user_id = $row['user_id'];
            $user_type = $row['user_type'];
            $user_name = strtoupper($row['first_name'] . " " . $row['last_name']);
            $body .= "<p>Welcome back, $user_name!</p>\n";
            $_SESSION['user_id'] = $user_id;
        }
    } catch (mysqli_sql_exception $e) {
        echo "<p>Error: unable to connect/insert record in the database.</p>";
        ++$errors;
    }

    if ($errors > 0) {
        echo "<p>Unable to proceed. Please return to the <a href='registraion login page.php?" . SID . "'>Home</a> page.</p>\n";;
    }
    if ($errors == 0 && $user_type == "1") {
        // Display options for admin user
        echo "<table>";
        //Button for admin portal
        echo "<tr>";
        echo "<td>";
        echo "<form method='post' " . " action='admin available cars.php?" . SID . "'>";
        echo "<input type='submit' name='submit' " . " value='View Admin Portal'>";
        echo "</form>";
        echo "</td>";
        echo "<td>";
        //Button for inserting a car
        echo "<form method='post' " . " action='insert a car.php?" . SID . "'>";
        echo "<input type='submit' name='submit' " . " value='Insert a New Car'>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
        echo "</table>";
        echo $body;
    }
    if ($errors == 0 && $user_type == "0")  {
        // Display options for regular user
        echo "<table>";
        echo "<tr>";
        echo "<td>";
        //Button for checking available cars
        echo "<form method='post' " . " action='available cars.php?" . SID . "'>";
        echo "<input type='submit' name='submit' " . " value='View available cars'>";
        echo "</form>";
        echo "</td>";
        echo "<td>";
        //Button for checking rented cars
        echo "<form method='post' " . " action='rented cars.php?" . SID . "'>";
        echo "<input type='submit' name='submit' " . " value='Your Rented Cars'>";
        echo "</form>";
        echo "</td>";
        echo "<td>";
        //Button for checking previously rented cars
        echo "<form method='post' " . " action='previously_rented.php?" . SID . "'>";
        echo "<input type='submit' name='submit' " . " value='Previous Rented'>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
        echo "</table>";
        echo $body;
    }
    include("footer.php")
    ?>
</body>


</html>
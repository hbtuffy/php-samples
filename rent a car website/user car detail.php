<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>User Portal</title>
</head>
<?php
include("header.php");

?>

<body>
    <h1>User Portal</h1>
    <h2>Car Details</h2>

    <?php
    //Portal control buttons
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

    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "my_rent_buddy";

    try {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $database);
    } catch (mysqli_sql_exception $e) {
        die($e->getCode() . ": " . $e->getMessage());
    }

    // Retrieve plate number from GET parameter
    if (isset($_POST["action"])) {
        $plate_number = trim($_POST["rego"]);
        $model = trim($_POST["model"]);
        $type = trim($_POST["type"]);
    }

    // Query the database to get car details
    $query = "SELECT * FROM available_car WHERE plates = '$plate_number' AND model = '$model' AND type = '$type'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Car found, display the details
        $car = $result->fetch_assoc();
        echo "<br>";
        echo "<b>Plate Number:</b> " . $car['plates'] . "<br>";
        echo "<b>Model:</b>  " . $car['model'] . "<br>";
        echo "<b>Type:</b>  " . $car['type'] . "<br>";
        echo "<b>Cost per Day:</b>  " . $car['cost_per_day'] . "<br>";
    } else {
        echo "Car not found";
    }

    // Close the connection
    $conn->close();

    ?>

    <p><a href='registraion login page.php'>Log Out</a></p>

    <?php
    include("footer.php")
    ?>
</body>

</html>
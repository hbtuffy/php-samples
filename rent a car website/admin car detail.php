<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Portal</title>
</head>
<?php
include("header.php");

?>

<body>
    <h1>Admin Portal</h1>
    <h2>Car Details</h2>

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

    // Retrieve details from GET parameter
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
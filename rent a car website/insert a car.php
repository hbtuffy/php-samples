<?php
session_start();
include('class_car.php');

$car = new inserting_car();
$car_res = "";



?>
<!DOCTYPE html>
<html>

<head>

    <title>Admin Portal</title>
</head>

<body>
    <?php include("header.php"); ?>
    <h1>Admin Portal</h1>
    <h2>Car Registration</h2>

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
    <form method="post" action="insert a car.php">
        <table>
            <tr>
                <td> Model: </td>
                <td><input type="text" name="model" /><br /></td>
            </tr>
            <tr>
                <td> Plates: </td>
                <td><input type="text" name="plates" /><br /></td>
            </tr>
            <tr>
                <td> Car Type: </td>
                <td><input type="text" name="type_car" /><br /></td>
            </tr>
            <tr>
                <td> Status:</td>
                <td>
                    <select name="status">
                        <option value="available">Available</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td> Cost per day: </td>
                <td><input type="number" name="cost_per_day" />$<br /></td>
            </tr>
            <tr>
                <td colspan="2" align="right"> <input type="reset" name="reset" value="Reset Form" class="button" /> &nbsp;<input type="submit" name="registration" value="Register Car" class="button" /></td>
            </tr>
        </table>
    </form>
    <br>
    <?php if (isset($_POST['registration'])) {
        $error = 0;
        if (empty($_POST["model"])) {
            echo "You cannot leave Model empty<br>";
            ++$error;
        }
        if (empty($_POST["plates"])) {
            echo "You cannot leave Plates empty<br>";
            ++$error;
        }
        if (empty($_POST["type_car"])) {
            echo "You cannot leave Car Type empty<br>";
            ++$error;
        }
        if (empty($_POST["status"])) {
            echo "You cannot leave Status empty<br>";
            ++$error;
        }
        if (empty($_POST["cost_per_day"])) {
            echo "You cannot leave Cost per day empty<br>";
            ++$error;
        }
        if ($error == 0) {
            $car_res = $car->insert_new_car($_POST);
            $error = 0;
        }
    }
echo "<p><a href='registraion login page.php'>Log Out</a></p>";

    
    ?>
    
</body>
<?php include("footer.php"); ?>


</html>
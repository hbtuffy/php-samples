<?php
session_start();
$body = "";
$errors = 0;
$email = "";
//get user type as it always gives 1 or 0
$user_type = stripslashes($_POST['user_type']);

//check if email is suitable
if (empty($_POST["email"])) {
    ++$errors;
    $body .= "<p>You need to enter an e-mail address.</p>\n";
} else {
    $email = stripslashes($_POST["email"]);
    if (preg_match("/@(gmail|hotmail|yahoo|outlook|icloud|admin)\.com$/i", $email) == 0) {

        ++$errors;
        $body .= "<p>You need to enter a valid e-mail address.</p>\n";
        $email = "";
    }
}
//check if password-1 is suitable
if (empty($_POST['password_1'])) {
    ++$errors;
    $body .= "<p>You need to enter a password.</p>\n";
    $password_1 = "";
} else
    $password = stripslashes($_POST['password_1']);
//check if password-2 is suitable
if (empty($_POST['password_2'])) {
    ++$errors;
    $body .= "<p>You need to enter a confirmation password.</p>\n";
    $password_2 = "";
} else
    $password2 = stripslashes($_POST['password_2']);

//check if password-1 and password-2 is suitable together
if ((!(empty($password_1))) && (!(empty($password_2)))) {
    if (strlen($password_1) < 6) {
        ++$errors;
        $body .= "<p>The password is too short.</p>\n";
        $password_1 = "";
        $password_2 = "";
    }
    if ($password_1 <> $password_2) {
        ++$errors;
        $body .= "<p>The passwords do not match.</p>\n";
        $password_1 = "";
        $password_2 = "";
    }
}
//Database login details
$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "my_rent_buddy";
$table_name = "user";

if ($errors == 0) {
    try {
        $conn = new mysqli($db_host, $db_user, $db_password);
        $select_db_result = $conn->select_db($db_name);
        //check if email is already in the database
        $select_user = "SELECT count(*) FROM $table_name where email='$email'";
        $qRes = $conn->query($select_user);
        $row = $qRes->fetch_row();
        if ($row[0] > 0) {
            $body .= "<p>The email address entered (" . htmlentities($email) . ") is already registered.</p>\n";
            ++$errors;
        }
    } catch (mysqli_sql_exception $e) {
        $body .= die($e->getCode() . ": " . $e->getMessage());
        ++$errors;
    }
}
//direct the user if not able to register
if ($errors > 0) {
    $body .= "<p>Return to the <a href='registraion login page.php?" . SID . "'>Home</a> page.</p>\n";
}
//direct the user if able to register
if ($errors == 0) {
    $first_name = stripslashes($_POST['first_name']);
    $last_name = stripslashes($_POST['last_name']);
    $phone_number = stripslashes($_POST['phone']);
    try {
        //create the user in the database and insert the informations
        $insert_student = "INSERT INTO $table_name (user_type,first_name,last_name,password,email,phone) " .
            "VALUES ('$user_type','$first_name','$last_name'," . "'" . md5($password) . "', '$email','$phone_number')";
        mysqli_query($conn, $insert_student);
        $user_id = $conn->insert_id;
        $_SESSION['user_id'] = $user_id;
        $conn->close();
    } catch (mysqli_sql_exception $e) {
        $body .= "<p>Unable to register the user</p>" . mysqli_error($conn);
        ++$errors;
    }
}
if ($errors == 0) {
    //Direct user to login with the details
    if ($user_type == "1") {
        $user_name = strtoupper("$first_name $last_name");
        $body .= "<p>Thank you, $user_name. ";
        $body .= "Your new manager ID is <strong>" . $_SESSION['user_id'] . "</strong>. Please login with your details to see your portal.</p>\n";
        // $body .= "<form method='post' " .     " action='user login.php/PHPSESSID=" . session_id() . "'>\n";
        $body .= "<a href='registraion login page.php?content=Login'>Login</a>\n";
        // $body .= "</form>\n";
    } else {
        $user_name = strtoupper("$first_name $last_name");;
        // $body .= "<form method='post' " .     " action='registraion login page.php?PHPSESSID=" . session_id() . "'>\n";
        // $body .= "<input type='submit' name='submit' " . " value='Login Portal'>\n";
        // $body .= "</form>\n";
        // $body .= "<table>";
        // $body .= "<tr>";
        // $body .= "<td>";
        // $body .= "<form method='post' " . " action='available cars.php?" . SID . "'>";
        // $body .= "<input type='submit' name='submit' " . " value='View available cars'>";
        // $body .= "</form>";
        // $body .= "</td>";
        // $body .= "<td>";
        // $body .= "<form method='post' " . " action='rented cars.php?" . SID . "'>";
        // $body .= "<input type='submit' name='submit' " . " value='Your Rented Cars'>";
        // $body .= "</form>";
        // $body .= "</td>";
        // $body .= "<td>";
        // $body .= "<form method='post' " . " action='previously_rented.php?" . SID . "'>";
        // $body .= "<input type='submit' name='submit' " . " value='Previous Rented'>";
        // $body .= "</form>";
        // $body .= "</td>";
        // $body .= "</tr>";
        // $body .= "</table>";
        $body .= "<p>Thank you, $user_name. ";
        $body .= "Your new user ID is <strong>" . $_SESSION['user_id'] . "</strong>. Please <strong>LOGIN</strong> with your details to see your portal.</p>\n";
        $body .= "<a href='registraion login page.php?content=Login'>Login</a>\n";
    }
}
?>
<!DOCTYPE html>
<html>


<head>
    <title>LogIn/REGISTER</title>
</head>
<?php include("header.php"); ?>

<body>
    <?php
    if ($user_type == "1") {
        echo "<h1>New Manager Registration</h1>";
    } else {
        echo "<h1>New customer registration<h1>";
        echo "<h2>Welcome to My Rent Buddy.<h2>";
    }

    ?>

    <?php
    echo $body;
    ?>
</body>
<?php include("footer.php"); ?>

</html>
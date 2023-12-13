<?php
session_start();
$body = "";
$errors = 0;
$email = "";

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
if (empty($_POST['password_1'])) {
    ++$errors;
    $body .= "<p>You need to enter a password.</p>\n";
    $password_1 = "";
} else
    $password = stripslashes($_POST['password_1']);

if (empty($_POST['password_2'])) {
    ++$errors;
    $body .= "<p>You need to enter a confirmation password.</p>\n";
    $password_2 = "";
} else
    $password2 = stripslashes($_POST['password_2']);

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

$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "music_conservatorium";
$table_name = "students";
if ($errors == 0) {
    try {
        $db_connection = mysqli_connect($db_host, $db_user, $db_password);
        $select_db_result = mysqli_select_db($db_connection, $db_name);
        $select_students = "SELECT count(*) FROM $table_name where email='$email'";
        $qRes = mysqli_query($db_connection, $select_students);
        $row = mysqli_fetch_row($qRes);
        if ($row[0] > 0) {
            $body .= "<p>The email address entered (" . htmlentities($email) . ") is already registered.</p>\n";
            ++$errors;
        }
    } catch (mysqli_sql_exception $e) {
        $body .= "<p>Unable to connect to the database </p>\n";
        ++$errors;
    }
}
if ($errors > 0) {
    $body .= "<p>Return to the <a href='student login.php?" . SID . "'>Student login</a> page.</p>\n";
}
if ($errors == 0) {
    $first_name = stripslashes($_POST['first_name']);
    $last_name = stripslashes($_POST['last_name']);
    $phone_number = stripslashes($_POST['phone']);
    try {
        $insert_student = "INSERT INTO $table_name (first_name,last_name,password_md5,email,phone_number) " .
            "VALUES ('$first_name','$last_name'," . "'" . md5($password) . "', '$email','$phone_number')";
        mysqli_query($db_connection, $insert_student);
        $student_id = mysqli_insert_id($db_connection);
        $_SESSION["student_id"] = $student_id;
        mysqli_close($db_connection);
    } catch (mysqli_sql_exception $e) {
        $body .= "<p>Unable to insert record</p>" . mysqli_error($db_connection);
        ++$errors;
    }
}
if ($errors == 0) {

    $student_name = "$first_name $last_name";
    $body .= "<p>Thank you, $student_name. ";
    $body .= "Your new student ID is <strong>" . $_SESSION['student_id'] . "</strong>.</p>\n";
}
if ($errors == 0) {
    $body .= "<form method='post' " .     " action='available classes.php?PHPSESSID=" . session_id() . "'>\n";
    $body .= "<input type='submit' name='submit' " . " value='View Available Classes'>\n";
    $body .= "</form>\n";
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>LogIn/REGISTER</title>
</head>

<body>
    <h1>Music Conservatorium
    </h1>
    <h2>New Student Registration
    </h2>
    <?php
    echo $body;
    ?>
</body>

</html>
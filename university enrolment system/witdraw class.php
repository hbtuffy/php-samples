<?php
session_start();
$Body = "";
$errors = 0;
$student_id = 0;

$errors = 0;
$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "music_conservatorium";

if (isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];
} else {
    $Body .= "<p>You have not logged in or registered. Please return to the <a href='student login.php'>Register / Log In page</a>.</p>";
    ++$errors;
}
if ($errors == 0) {
    if (isset($_GET['class_id'])) {
        $class_id = $_GET['class_id'];
    } else {
        $Body .=
            "<p>You have not enrolled in any class. Please return to the <a href='available classes.php?" . SID . "'>Available classes page</a>.</p>";
        ++$errors;
    }
}

if ($errors == 0) {
    try {
        $db_connection = mysqli_connect($db_host, $db_user, $db_password);
        $result = mysqli_select_db($db_connection, $db_name);
        $display_data = date("Y-m-d");
        $db_date = date("Y-m-d H:i:s");
        $table_enrolments = "enrolments";
        // $insert_enrolments = "INSERT INTO $table_enrolments (class_id,student_id,date_selected) VALUES ($class_id, $student_id, '$db_date')";
        $insert_enrolments = "DELETE FROM $table_enrolments WHERE class_id=$class_id AND student_id=$student_id";
        $qRes = mysqli_query($db_connection, $insert_enrolments);
        $Body .= "<p>Your enrollment for class # " . " $class_id has been removed on $display_data.</p>\n";
        mysqli_close($db_connection);
    } catch (mysqli_sql_exception $e) {
        $Body .= "<p>Unable to execute the query.</p>\n";
        ++$errors;
    }
}
if ($student_id > 0)
    $Body .= "<p>Return to the <a href='available classes.php?" . SID . "'>Available classes</a> page.</p>\n";
else
    $Body .= "<p>Please <a href='student login.php'>Register or Log In</a> to use this page.</p>\n";

if ($errors == 0)
    setcookie("LastRequestDate", urlencode($display_data), time() + 60 * 60 * 24 * 7); 

?>
<!DOCTYPE html>
<html>

<head>
    <title>Request enrollment</title>
</head>

<body>
    <h1>Music Conservatorium</h1>
    <h2>Class requested Registration</h2>
    <?php
    echo $Body;
    ?>
</body>

</html>
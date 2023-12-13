<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Current Student Login</title>
</head>

<body>
    <h1>Music Conservatorium </h1>
    <h2>Current Student Registration </h2>

    <?php
    $errors = 0;
    $db_host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "music_conservatorium";
    $table_name = "students";


    try {
        $db_connection = mysqli_connect($db_host, $db_user, $db_password, $db_name);
        $select_student = "SELECT student_id, first_name, last_name FROM $table_name" . " where email='" . stripslashes($_POST['email']) . "' and password_md5='" . md5(stripslashes($_POST['password'])) . "'";
        $qRes = mysqli_query($db_connection, $select_student);

        if (mysqli_num_rows($qRes) == 0) {
            echo "<p>The e-mail address/password " . " combination entered is not valid. </p>\n";
            ++$errors;
        } else {
            $row = mysqli_fetch_assoc($qRes);
            $student_id = $row['student_id'];
            $student_name = $row['first_name'] . " " . $row['last_name'];
            echo "<p>Welcome back, $student_name!</p>\n";
            $_SESSION['student_id'] = $student_id;
        }
    } catch (mysqli_sql_exception $e) {
        echo "<p>Error: unable to connect/insert record in the database.</p>";
        ++$errors;
    }

    if ($errors > 0) {
        echo "<p>Return to the <a href='student login.php?" . SID . "'>Student login</a> page.</p>\n";;
    }
    if ($errors == 0) {
        echo "<form method='post' " . " action='available classes.php?" . SID . "'>\n";
        echo "<input type='submit' name='submit' " . " value='View available classes'>\n";
        echo "</form>\n";
    }
    ?>
</body>

</html>